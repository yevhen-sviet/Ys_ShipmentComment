<?php
/**
 * Created by Yevhen Sviet
 */

declare(strict_types=1);

namespace Ys\ShipmentComment\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Ys\ShipmentComment\Model\Config;

class Data extends AbstractHelper
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
     * Checks if the shipment comment feature is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->config->isEnabled();
    }
}
