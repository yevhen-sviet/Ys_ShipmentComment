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
    /**
     * Constructor
     *
     * @param Config $config
     */
    public function __construct(private Config $config)
    {
    }

    /**
     * Retrieves configuration settings for the checkout process
     *
     * @return array
     */
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
