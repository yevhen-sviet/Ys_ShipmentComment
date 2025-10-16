<?php
declare(strict_types=1);

namespace Ys\ShipmentComment\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Shipping\Model\Config as ShippingConfig;

class ShippingMethods implements ArrayInterface
{
    public function __construct(private ShippingConfig $shippingConfig) {}

    public function toOptionArray(): array
    {
        $options = [];
        foreach ($this->shippingConfig->getAllCarriers() as $carrierCode => $carrier) {
            if (!$carrier->isActive()) {
                continue;
            }
            
            $title = (string)($carrier->getConfigData('title') ?? strtoupper($carrierCode));
            $allowed = $carrier->getAllowedMethods();
            if (is_array($allowed)) {
                foreach ($allowed as $methodCode => $methodTitle) {
                    $value = $carrierCode . '_' . $methodCode;
                    $label = sprintf('%s — %s (%s)', $title, $methodTitle, $value);
                    $options[] = [
                        'value' => $value, 
                        'label' => $label
                    ];
                }
            }
        }

        usort($options, function ($a, $b) { 
            return strcmp($a['label'], $b['label']); 
        });

        return $options;
    }
}
