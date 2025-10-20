/**
 * Created by Yevhen Sviet
 */
define([
    'uiComponent',
    'ko',
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/shipping-save-processor',
    'Magento_Checkout/js/action/set-shipping-information',
    'mage/translate'
], function (Component, ko, $, quote, shippingSaveProcessor, setShippingInformationAction, $t) {
    'use strict';

    function methodKey(method) {
        if (!method) return '';
        return (method.carrier_code || '') + '_' + (method.method_code || '');
    }

    return Component.extend({
        defaults: {
            template: 'Ys_ShipmentComment/shipment-comment',
            value: ko.observable(''),
            isVisible: ko.observable(false),
            isRequired: ko.observable(false),
            errorMessage: ko.observable('')
        },

        initialize: function () {
            this._super();

            var cfg = (window.checkoutConfig && window.checkoutConfig.ys_shipmentcomment) || {};
            var requiredList   = cfg.requiredMethods || [];
            var requireEnabled = !!cfg.requireEnabled;

            var sync = function () {
                var key = methodKey(quote.shippingMethod());
                var selected = requiredList.indexOf(key) !== -1;

                this.isVisible(!!selected);
                this.isRequired(selected && requireEnabled);

                if (!this.isVisible() || !this.isRequired()) this.errorMessage('');
            }.bind(this);

            sync();
            quote.shippingMethod.subscribe(sync);

            this.value.subscribe(function (val) {
                if (setShippingInformationAction && typeof setShippingInformationAction.setShipmentComment === 'function') {
                    setShippingInformationAction.setShipmentComment(val);
                }
                this.validate();
            }.bind(this));

            var original = shippingSaveProcessor.saveShippingInformation;
            shippingSaveProcessor.saveShippingInformation = function () {
                if (this.isVisible() && this.isRequired() && !this.validate()) {
                    var d = $.Deferred(); d.reject(); return d.promise();
                }

                var shippingAddress = quote.shippingAddress();
                if (shippingAddress && this.isVisible()) {
                    shippingAddress['extension_attributes'] = shippingAddress['extension_attributes'] || {};
                    shippingAddress['extension_attributes']['shipment_comment'] = this.value();
                }

                return original.apply(shippingSaveProcessor, arguments);
            }.bind(this);

            return this;
        },

        validate: function () {
            if (this.isVisible() && this.isRequired() && !String(this.value() || '').trim()) {
                this.errorMessage($t('Shipment comment is required for the selected shipping method.'));
                return false;
            }
            this.errorMessage('');
            return true;
        }
    });
});
