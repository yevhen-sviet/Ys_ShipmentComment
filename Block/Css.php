<?php
declare(strict_types=1);

namespace Ys\ShipmentComment\Block;

use Magento\Framework\View\Element\Template;
use Ys\ShipmentComment\Model\Config;

class Css extends Template
{
    public function __construct(
        Template\Context $context,
        private Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getCssByPath(): string
    {
        if (!$this->config->isEnabled()) {
            return '';
        }
        $path = (string)($this->getData('config_path') ?? '');
        if ($path === Config::XML_CHECKOUT_CSS) return $this->config->getCheckoutCss();
        if ($path === Config::XML_ACCOUNT_CSS) return $this->config->getAccountCss();
        return '';
    }
}
