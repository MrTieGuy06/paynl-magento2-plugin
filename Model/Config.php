<?php

namespace Paynl\Payment\Model;

use Magento\Framework\App\ProductMetadataInterface;
use Magento\Payment\Helper\Data;
use Magento\Store\Model\Store;

/**
 * Get / Set configuration for the PAY api and Magento settings.
 */
class Config
{
    public const FINISH_PAY = 'paynl/order/finish';
    public const PENDING_PAY = 'paynl/order/pending';
    public const CANCEL_PAY = 'paynl/order/cancel';
    public const FINISH_STANDARD = 'checkout/onepage/success';
    public const ORDERSTATUS_PAID = 100;
    public const ORDERSTATUS_PENDING = array(20, 25, 40, 50, 90);
    public const ORDERSTATUS_DENIED = -63;
    public const ORDERSTATUS_CANCELED = -90;
    public const ORDERSTATUS_VERIFY = 85;

    /** @var  Store */
    private $store;

    /** @var  Resources */
    private $resources;

    /** @var  Paynl\Payment\Helper\PayHelper */
    protected $helper;

    /**
     * @var ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * @var Data
     */
    protected $paymentHelper;

    /** @array  Brands */
    public $brands = [
        "paynl_payment_afterpay" => "14",
        "paynl_payment_afterpay_international" => "14",
        "paynl_payment_alipay" => "82",
        "paynl_payment_amazonpay" => "22",
        "paynl_payment_amex" => "9",
        "paynl_payment_applepay" => "114",
        "paynl_payment_bataviacadeaukaart" => "255",
        "paynl_payment_beautycadeau" => "288",
        "paynl_payment_biercheque" => "204",
        "paynl_payment_biller" => "252",
        "paynl_payment_billink" => "16",
        "paynl_payment_bioscoopbon" => "87",
        "paynl_payment_blik" => "234",
        "paynl_payment_bloemencadeaukaart" => "192",
        "paynl_payment_boekenbon" => "219",
        "paynl_payment_capayable_gespreid" => "19",
        "paynl_payment_cartebleue" => "11",
        "paynl_payment_cashly" => "43",
        "paynl_payment_creditclick" => "99",
        "paynl_payment_cult" => "291",
        "paynl_payment_dankort" => "58",
        "paynl_payment_decadeaukaart" => "189",
        "paynl_payment_dinerbon" => "198",
        "paynl_payment_eps" => "79",
        "paynl_payment_fashioncheque" => "27",
        "paynl_payment_fashiongiftcard" => "28",
        "paynl_payment_festivalcadeaukaart" => "144",
        "paynl_payment_gezondheidsbon" => "30",
        "paynl_payment_giropay" => "3",
        "paynl_payment_givacard" => "61",
        "paynl_payment_good4fun" => "207",
        "paynl_payment_googlepay" => "176",
        "paynl_payment_horsesandgifts" => "300",
        "paynl_payment_huisentuincadeau" => "117",
        "paynl_payment_ideal" => "1",
        "paynl_payment_in3business" => "297",
        "paynl_payment_incasso" => "13",
        "paynl_payment_instore" => "164",
        "paynl_payment_klarna" => "15",
        "paynl_payment_klarnakp" => "15",
        "paynl_payment_maestro" => "33",
        "paynl_payment_mistercash" => "2",
        "paynl_payment_monizze" => "183",
        "paynl_payment_mooigiftcard" => "294",
        "paynl_payment_multibanco" => "141",
        "paynl_payment_nexi" => "76",
        "paynl_payment_overboeking" => "12",
        "paynl_payment_onlinebankbetaling" => "258",
        "paynl_payment_parfumcadeaukaart" => "210",
        "paynl_payment_payconiq" => "138",
        "paynl_payment_paypal" => "21",
        "paynl_payment_paysafecard" => "24",
        "paynl_payment_podiumcadeaukaart" => "29",
        "paynl_payment_postepay" => "10",
        "paynl_payment_przelewy24" => "93",
        "paynl_payment_prontowonen" => "270",
        "paynl_payment_shoesandsneakers" => "2937",
        "paynl_payment_sodexo" => "186",
        "paynl_payment_sofortbanking" => "4",
        "paynl_payment_sofortbanking_hr" => "4",
        "paynl_payment_sofortbanking_ds" => "4",
        "paynl_payment_spraypay" => "20",
        "paynl_payment_telefonischbetalen" => "173",
        "paynl_payment_trustly" => "213",
        "paynl_payment_visamastercard" => "7",
        "paynl_payment_vvvgiftcard" => "25",
        "paynl_payment_webshopgiftcard" => "26",
        "paynl_payment_wechatpay" => "23",
        "paynl_payment_wijncadeau" => "135",
        "paynl_payment_winkelcheque" => "195",
        "paynl_payment_yourgift" => "31",
        "paynl_payment_yourgreengift" => "246",
    ];

    /**
     * Index constructor.
     * @param Store $store
     * @param \Magento\Framework\View\Element\Template $resources
     * @param \Paynl\Payment\Helper\PayHelper $helper
     * @param ProductMetadataInterface $productMetadata
     * @param Data $paymentHelper
     */
    public function __construct(
        Store $store,
        \Magento\Framework\View\Element\Template $resources,
        \Paynl\Payment\Helper\PayHelper $helper,
        ProductMetadataInterface $productMetadata,
        Data $paymentHelper
    ) {
        $this->store = $store;
        $this->resources = $resources;
        $this->helper = $helper;
        $this->productMetadata = $productMetadata;
        $this->paymentHelper = $paymentHelper;
    }

    /**
     * @param string $defaultValue
     * @return mixed|string
     */
    public function getVersion(string $defaultValue = '')
    {
        $composerFilePath = sprintf('%s/%s', rtrim(__DIR__, '/'), '../composer.json');
        if (file_exists($composerFilePath)) {
            $composer = json_decode(file_get_contents($composerFilePath), true);

            if (isset($composer['version'])) {
                return $composer['version'];
            }
        }

        return $defaultValue;
    }

    /**
     * @return string
     */
    public function getMagentoVersion()
    {
        $version = $this->productMetadata->getVersion();
        return $version;
    }

    /**
     * @return string
     */
    public function getPHPVersion()
    {
        return substr(phpversion(), 0, 3);
    }

    /**
     * @param Store $store
     * @return void
     */
    public function setStore(Store $store)
    {
        $this->store = $store;
    }

    /**
     * @return boolean
     */
    public function isSkipFraudDetection()
    {
        return $this->sherpaEnabled() === true || $this->store->getConfig('payment/paynl/skip_fraud_detection') == 1;
    }

    /**
     * @return boolean
     */
    public function isTestMode()
    {
        $ip = $this->helper->getClientIp();

        $ipconfig = $this->store->getConfig('payment/paynl/testipaddress');

        if (!empty($ipconfig)) {
            $allowed_ips = explode(',', $ipconfig);
            if (
                in_array($ip, $allowed_ips) &&
                filter_var($ip, FILTER_VALIDATE_IP) &&
                strlen($ip) > 0 &&
                count($allowed_ips) > 0
            ) {
                return true;
            }
        }
        return $this->store->getConfig('payment/paynl/testmode') == 1;
    }

    /**
     * @return boolean
     */
    public function isSendDiscountTax()
    {
        return $this->store->getConfig('payment/paynl/discount_tax') == 1;
    }

    /**
     * @return boolean
     */
    public function isNeverCancel()
    {
        return $this->store->getConfig('payment/paynl/never_cancel') == 1;
    }

    /**
     * @return boolean
     */
    public function isAlwaysBaseCurrency()
    {
        return $this->store->getConfig('payment/paynl/always_base_currency') == 1;
    }

    /**
     * @param string $paymentMethod
     * @return boolean
     */
    public function isPaymentMethodActive(string $paymentMethod)
    {
        return $this->store->getConfig('payment/' . $paymentMethod . '/active') == 1;
    }

    /**
     * @return boolean
     */
    public function useSkuId()
    {
        return $this->store->getConfig('payment/paynl/use_sku_id') == 1;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        $language = $this->store->getConfig('payment/paynl/language');

        return $language ? $language : 'nl'; //default nl
    }

    /**
     * @param string $methodCode
     * @return string
     */
    public function getPaymentOptionId(string $methodCode)
    {
        return $this->store->getConfig('payment/' . $methodCode . '/payment_option_id');
    }

    /**
     * @param string $methodCode
     * @return string
     */
    public function getPendingStatus(string $methodCode)
    {
        return $this->store->getConfig('payment/' . $methodCode . '/order_status');
    }

    /**
     * @param string $methodCode
     * @return string
     */
    public function getAuthorizedStatus(string $methodCode)
    {
        return $this->store->getConfig('payment/' . $methodCode . '/order_status_authorized');
    }

    /**
     * @param string $methodCode
     * @return string
     */
    public function getPaidStatus(string $methodCode)
    {
        return $this->store->getConfig('payment/' . $methodCode . '/order_status_processing');
    }

    /**
     * @param string $methodCode
     * @return boolean
     */
    public function ignoreB2BInvoice(string $methodCode)
    {
        return $this->store->getConfig('payment/' . $methodCode . '/turn_off_invoices_b2b') == 1;
    }

    /**
     * @return boolean
     */
    public function ignoreManualCapture()
    {
        return $this->store->getConfig('payment/paynl/auto_capture') != 0;
    }

    /**
     * @return boolean
     */
    public function autoCaptureEnabled()
    {
        return $this->store->getConfig('payment/paynl/auto_capture') >= 1;
    }

    /**
     * @return boolean
     */
    public function wuunderAutoCaptureEnabled()
    {
        return $this->store->getConfig('payment/paynl/auto_capture') == 2;
    }

    /**
     * @return boolean
     */
    public function sherpaEnabled()
    {
        return $this->store->getConfig('payment/paynl/auto_capture') == 3;
    }

    /**
     * @return boolean
     */
    public function autoVoidEnabled()
    {
        return $this->store->getConfig('payment/paynl/auto_void') == 1;
    }

    /**
     * @return boolean
     */
    public function sendEcommerceAnalytics()
    {
        return $this->store->getConfig('payment/paynl/google_analytics_ecommerce') == 1;
    }

    /**
     * @return boolean
     */
    public function getPendingPage()
    {
        return $this->store->getConfig('payment/paynl/pay_pending_page') == 1;
    }

    /**
     * @param string $methodCode
     * @return string
     */
    public function getSuccessPage(string $methodCode)
    {
        return $this->store->getConfig('payment/' . $methodCode . '/custom_success_page');
    }

    /**
     * Configures the sdk with the API token and serviceId
     * @param boolean $useGateway
     * @return boolean TRUE when config loaded, FALSE when the apitoken or serviceId are empty
     */
    public function configureSDK($useGateway = false)
    {
        $apiToken = $this->getApiToken();
        $serviceId = $this->getServiceId();
        $tokencode = $this->getTokencode();
        $gateway = $this->getFailoverGateway();

        if (!empty($tokencode)) {
            \Paynl\Config::setTokenCode($tokencode);
        }

        if (!empty($apiToken) && !empty($serviceId)) {
            if ($useGateway && !empty($gateway) && substr(trim($gateway), 0, 4) === "http") {
                \Paynl\Config::setApiBase(trim($gateway));
            }
            \Paynl\Config::setApiToken($apiToken);
            \Paynl\Config::setServiceId($serviceId);

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getApiToken()
    {
        return trim((string)$this->store->getConfig('payment/paynl/apitoken_encrypted'));
    }

    /**
     * @return string
     */
    public function getTokencode()
    {
        return trim((string)$this->store->getConfig('payment/paynl/tokencode'));
    }

    /**
     * @return string
     */
    public function getServiceId()
    {
        return trim((string)$this->store->getConfig('payment/paynl/serviceid'));
    }

    /**
     * @return string|null
     */
    public function getFailoverGateway()
    {
        $gateway = $this->store->getConfig('payment/paynl/failover_gateway_select');
        if ($gateway == 'custom') {
            return trim((string)$this->store->getConfig('payment/paynl/failover_gateway'));
        }
        return $gateway;
    }

    /**
     * @param string $methodCode
     * @return mixed
     */
    public function getIconUrl(string $methodCode)
    {
        $brandId = $this->store->getConfig('payment/' . $methodCode . '/brand_id');
        if (empty($brandId)) {
            $brandId = $this->brands[$methodCode];
        }
        return $this->resources->getViewFileUrl(
            "Paynl_Payment::logos/" . $brandId . ".png",
            array(
                'area' => 'frontend',
            )
        );
    }

    /**
     * @param string $issuerId
     * @return mixed
     */
    public function getIconUrlIssuer(string $issuerId)
    {
        return $this->resources->getViewFileUrl(
            "Paynl_Payment::logos_issuers/qr-" . $issuerId . ".svg",
            array(
                'area' => 'frontend',
            )
        );
    }

    /**
     * @return string
     */
    public function getUseAdditionalValidation()
    {
        return $this->store->getConfig('payment/paynl/use_additional_validation');
    }

    /**
     * @return string
     */
    public function getCancelURL()
    {
        return $this->store->getConfig('payment/paynl/cancelurl');
    }

    /**
     * @return string
     */
    public function getDefaultPaymentOption()
    {
        return $this->store->getConfig('payment/paynl/default_payment_option');
    }

    /**
     * @return string
     */
    public function getCustomExchangeUrl()
    {
        return $this->store->getConfig('payment/paynl/custom_exchange_url');
    }

    /**
     * @return string
     */
    public function registerPartialPayments()
    {
        return $this->store->getConfig('payment/paynl/register_partial_payments');
    }

    /**
     * @return string|null
     */
    public function refundFromPay()
    {
        return $this->store->getConfig('payment/paynl/allow_refund_from_pay');
    }

    /**
     * @param string $paymentProfileId
     * @return string
     */
    public function getPaymentmethodCode(string $paymentProfileId)
    {
        # Get all PAY. methods
        $paymentMethodList = $this->paymentHelper->getPaymentMethods();

        foreach ($paymentMethodList as $key => $value) {
            if (strpos($key, 'paynl_') !== false && $key != 'paynl_payment_paylink') {
                $code = $this->store->getConfig('payment/' . $key . '/payment_option_id');
                if ($code == $paymentProfileId) {
                    return $key;
                }
            }
        }
    }
}
