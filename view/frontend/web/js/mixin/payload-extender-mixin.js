define([
  'Magento_Checkout/js/model/quote'
], function (quote) {
  'use strict';

  return function (originalExtender) {
    return function (payload) {
      payload = originalExtender(payload) || payload || {};

      var addr = quote.shippingAddress && quote.shippingAddress();
      var value  = '';

      if (addr && addr['extension_attributes'] && addr['extension_attributes']['shipment_comment'] !== undefined) {
        value = addr['extension_attributes']['shipment_comment'];
      }

      payload.addressInformation = payload.addressInformation || {};
      payload.addressInformation.extension_attributes = payload.addressInformation.extension_attributes || {};

      if (typeof value === 'string') {
        payload.addressInformation.extension_attributes.shipment_comment = value;
      }

      return payload;
    };
  };
});
