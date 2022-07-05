<?php

namespace Paynl\Payment\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\Store;
use Paynl\Result\Transaction\Transaction;
use Paynl\Payment\Model\Config;
use Magento\Sales\Model\Order;
use \Paynl\Payment\Helper\PayHelper;
use \Paynl\Payment\Model\PayPayment;

class OrderSaveCommitAfter implements ObserverInterface
{

    /**
     *
     * @var Magento\Store\Model\Store;
     */
    private $store;

    /**
     *
     * @var \Paynl\Payment\Model\Config
     */
    private $config;

    /**
     * @var PayPayment
     */
    private $payPayment;

    public function __construct(
        Config $config,
        Store $store,
        PayPayment $payPayment
    ) {
        $this->config = $config;
        $this->store = $store;
        $this->payPayment = $payPayment;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $this->config->setStore($order->getStore());

        $result = json_decode(file_get_contents('php://input'), true);
        $action = (!empty($result['action'])) ? $result['action'] : '';

        if ($this->config->autoCaptureEnabled()) {
            if (($order->getState() == Order::STATE_PROCESSING || $action === "track_and_trace_updated") && !$order->hasInvoices() && $order->hasShipments()) {
                $data = $order->getPayment()->getData();

                if (!empty($data['last_trans_id'])) {
                    $bHasAmountAuthorized = !empty($data['base_amount_authorized']);
                    $amountPaid = isset($data['amount_paid']) ? $data['amount_paid'] : null;
                    $amountRefunded = isset($data['amount_refunded']) ? $data['amount_refunded'] : null;

                    if ($bHasAmountAuthorized && $amountPaid === null && $amountRefunded === null) {
                        payHelper::logNotice('AUTO-CAPTURING ' . $data['last_trans_id'], [], $order->getStore());
                        try {
                            \Paynl\Config::setApiToken($this->config->getApiToken());

                            # Auto Capture for Wuunder
                            if ($this->config->wuunderAutoCaptureEnabled()) {
                                \Paynl\Transaction::capture($data['last_trans_id']);
                                $transaction = \Paynl\Transaction::get($data['last_trans_id']);
                                $this->payPayment->processPaidOrder($transaction, $order);
                            } elseif ($this->config->autoCaptureEnabled()) {
                                \Paynl\Transaction::capture($data['last_trans_id']);
                            }
                            $strResult = 'Success';
                        } catch (\Exception $e) {
                            payHelper::logCritical('Order PAY error: ' . $e->getMessage() . ' EntityId: ' . $order->getEntityId(), [], $order->getStore());
                            $strResult = 'Failed. Errorcode: PAY-MAGENTO2-003. See docs.pay.nl for more information';
                        }

                        $order->addStatusHistoryComment(__('PAY. - Performed auto-capture. Result: ') . $strResult, false)->save();
                    }
                }
            }
        }
    }
}
