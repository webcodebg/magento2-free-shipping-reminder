# Webcode_FreeShippingReminder

Free-shipping reminder in the cart: tells the customer how much is left to reach free shipping, using either the free shipping method's threshold or a subtotal you set.

## How

- The reminder shows at the top of the mini cart and of the cart summary: the message plus a progress bar *subtotal / threshold*. It follows the cart without a page reload, and hides when the cart is empty or the subtotal reaches the threshold.
- Default text: *You only have %1 left for free delivery* (translated; Bulgarian included), where `%1` is the remaining amount in the store's currency.
- Luma and Hyvä are both supported.

## Configuration

Stores → Configuration → Sales → Checkout → **Free Shipping Reminder**, per store view:

- Enable
- Use Free Shipping Method Value — take the threshold from the Free Shipping method (*Minimum Order Amount*); set to *No* to enter your own below.
- Free shipping subtotal — your own threshold.
- Message — your own text, `%1` for the remaining amount, HTML allowed; empty uses the default translated text.

## Installation

Webcode's free modules are installed from our own Composer repository — they are not published on Packagist.

```bash
composer config repositories.webcode composer https://repo.webcode.bg/public
composer require webcode/module-free-shipping-reminder
bin/magento module:enable Webcode_FreeShippingReminder
bin/magento setup:upgrade
```

## Compatibility

Magento Open Source and Mage-OS 2.4.7–2.4.9, PHP 8.3–8.5.

## License

[OSL-3.0](LICENSE.txt) and [AFL-3.0](LICENSE_AFL.txt), like Magento itself.

## About

Made by [Webcode](https://webcode.bg/), a Magento development and support studio from Bulgaria. Module page: <https://webcode.bg/en/free-shipping-reminder>.
