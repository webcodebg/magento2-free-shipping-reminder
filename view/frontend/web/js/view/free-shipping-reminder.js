define([
    'ko',
    'uiComponent',
    'mage/translate',
    'Magento_Customer/js/customer-data',
    'Magento_Catalog/js/price-utils'
], function (ko, Component, $t, customerData, priceUtils) {
    'use strict';
    var subtotalAmount;
    var percentage;
    var residue;

    return Component.extend({
        defaults: {
            // Текстът от настройките на store-а ("%1" е оставащата сума);
            // празен — преводимият текст по подразбиране.
            message: ''
        },
        displaySubtotal: ko.observable(true),
        maxprice: maxPrice.toFixed(2),
        /**
         * @override
         */
        initialize: function () {
            this._super();
            this.cart = customerData.get('cart');
        },

        getTotalCartItems: function () {
            return customerData.get('cart')().summary_count;
        },

        getPercentage: function () {
            subtotalAmount = customerData.get('cart')().subtotalAmount;
            if (subtotalAmount > maxPrice) {
                subtotalAmount = maxPrice;
            }
            percentage = ((subtotalAmount * 100) / maxPrice);
            return percentage;
        },

        getResidualValue: function () {
            subtotalAmount = customerData.get('cart')().subtotalAmount;
            if (subtotalAmount > maxPrice) {
                subtotalAmount = maxPrice;
            }
            residue = maxPrice - subtotalAmount;
            return residue.toFixed(2);
        },

        getFormattedPrice: function (price) {
            return priceUtils.formatPrice(price, priceFormat);
        },

        getMessage: function () {
            var message = this.message || $t('You only have %1 left for free delivery');

            return message.split('%1').join(this.getFormattedPrice(this.getResidualValue()));
        }
    });
});
