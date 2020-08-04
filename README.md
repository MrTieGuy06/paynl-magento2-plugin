<p align="center">
    <img src="https://www.pay.nl/uploads/1/brands/main_logo.png" />
</p>
<h1 align="center">PAY. Magento2 plugin</h1>
  
# Description

With this plug-in by PAY. you can easily add all desired payment methods to your Magento 2 webshop. Please refer to https://www.pay.nl (Dutch) for an overview of all features and services.

- [Description](#description)
- [Available payment methods](#available-payment-methods)
- [Installation](#installation)
- [Update instructions](#update-instructions)
- [Usage](#usage)
- [Support](#support)

# Available payment methods

Bank Payments  | Creditcards | Gift cards & Vouchers | Pay by invoice | Others | 
:-----------: | :-----------: | :-----------: | :-----------: | :-----------: |
iDEAL + QR |Visa | VVV Cadeaukaart | AfterPay | PayPal |
Bancontact + QR |  Mastercard | Webshop Giftcard | Achteraf betalen via Billink | WeChatPay | 
Giropay |American Express | FashionCheque | Focum AchterafBetalen.nl | AmazonPay |
MyBank | Carte Bancaire | Podium Cadeaukaart | Capayable Achteraf Betalen | Cashly | 
SOFORT | PostePay | Gezondheidsbon | in3 keer betalen, 0% rente | Pay Fixed Price (phone) |
Maestro | Dankort | Fashion Giftcard | Klarna | Instore Payments (POS) |
Bank Transfer | Cartasi | GivaCard | SprayPay | Przelewy24 | 
| Tikkie | | YourGift | Creditclick | Apple Pay | 
| Multibanco | | Paysafecard | | Payconiq
| | | Huis en Tuin Cadeau

# Installation
#### Installing

In command line, navigate to the installation directory of Magento2

Enter the following commands:

```
composer require paynl/magento2-plugin
php bin/magento setup:upgrade
php bin/magento cache:clean
```

The plugin is now installed


##### Setup

1. Log into the Magento Admin
2. Go to *Stores* / *Configuration*
3. Go to *Sales* / *Payment Methods*
4. Scroll down to find the PAY. Settings
5. Enter the API token and serviceID (these can be found in the PAY. Admin Panel --> https://admin.pay.nl/programs/programs
6. Save the settings
7. Enable the desired payment methods and set allowed countries
8. Save the settings

Go to the *Manage* / *Services* tab in the PAY. Admin Panel to enable extra payment methods.   

#### Update instructions

In command line, navigate to the installation directory of Magento2

Enter the following commands:

```
composer update paynl/magento2-plugin paynl/sdk
php bin/magento setup:upgrade
php bin/magento cache:clean
```

The plugin has now been updated

# Usage

**More information on this plugin can be found on https://docs.pay.nl/plugins#magento-2**

# Support
https://www.pay.nl

Contact us: support@pay.nl
