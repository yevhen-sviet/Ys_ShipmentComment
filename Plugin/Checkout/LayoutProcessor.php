<?php
/**
 * Created by Yevhen Sviet
 */

declare(strict_types=1);

namespace Ys\ShipmentComment\Plugin\Checkout;

use Ys\ShipmentComment\Helper\Data as Helper;

class LayoutProcessor
{
    public function __construct(private Helper $helper) {}

    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ): array {
        if (!$this->helper->isEnabled()) {
            return $jsLayout;
        }

        $components =& $jsLayout['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children'];

        if (!isset($components['shippingAdditional'])) {
            $components['shippingAdditional'] = [
                'component'   => 'uiComponent',
                'displayArea' => 'shippingAdditional',
                'children'    => []
            ];
        } elseif (!isset($components['shippingAdditional']['component'])) {
            $components['shippingAdditional']['component'] = 'uiComponent';
            $components['shippingAdditional']['displayArea'] = 'shippingAdditional';
            $components['shippingAdditional']['children'] = $components['shippingAdditional']['children'] ?? [];
        }

        $components['shippingAdditional']['children']['ys-shipment-comment'] = [
            'component' => 'Ys_ShipmentComment/js/view/shipment-comment',
            'sortOrder' => 900,
            'config'    => [
                'template'    => 'Ys_ShipmentComment/shipment-comment',
                'customScope' => 'shippingAddress'
            ],
            'dataScope' => 'shippingAddress.extension_attributes.shipment_comment',
            'provider'  => 'checkoutProvider',
            'deps'      => 'checkoutProvider'
        ];

        return $jsLayout;
    }
}
