/**
 * Created by Yevhen Sviet
 */
define([
    'jquery',
    'Magento_Checkout/js/model/quote',
    'ko'
], function ($, quote, ko) {
    'use strict';

    var sharedValue = ko.observable('');

    return function (originalAction) {
        var wrapped = function (payload) {
            payload = payload || {};
            payload.addressInformation = payload.addressInformation || {};
            payload.addressInformation.extension_attributes = payload.addressInformation.extension_attributes || {};
            payload.addressInformation.extension_attributes.shipment_comment = sharedValue();
            return originalAction(payload);
        };

        wrapped.setShipmentComment = function (val) { 
            sharedValue(val); 
        };

        return wrapped;
    };
});
