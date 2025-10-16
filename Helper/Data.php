<?php
declare(strict_types=1);

namespace Ys\ShipmentComment\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Ys\ShipmentComment\Model\Config;

class Data extends AbstractHelper
{
    public function __construct(private Config $config) {}

    public function isEnabled(): bool
    {
        return $this->config->isEnabled();
    }
}
