<?php
/**
 * Created by Yevhen Sviet
 */

declare(strict_types=1);

namespace Ys\ShipmentComment\Model\Checkout;

use Magento\Checkout\Model\ConfigProviderInterface;
use Ys\ShipmentComment\Model\Config;

class ConfigProvider implements ConfigProviderInterface
{
    public function __construct(private Config $config) {}

    public function getConfig(): array
    {
        return [
            'ys_shipmentcomment' => [
                'enabled'         => $this->config->isEnabled(),
                'requireEnabled'  => $this->config->isRequireEnabled(),
                'requiredMethods' => $this->config->getRequiredMethods()
            ]
        ];
    }
}
