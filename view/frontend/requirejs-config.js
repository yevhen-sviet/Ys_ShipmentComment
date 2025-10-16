var config = {
    map: {
        '*': {
            ysShipmentComment: 'Ys_ShipmentComment/js/view/shipment-comment'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'Ys_ShipmentComment/js/mixin/set-shipping-information-mixin': true
            }
        }
    }
};