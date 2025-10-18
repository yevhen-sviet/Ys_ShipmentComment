<?php
/**
 * Created by Yevhen Sviet
 */

declare(strict_types=1);

namespace Ys\ShipmentComment\Block;

use Magento\Framework\View\Element\Template;
use Ys\ShipmentComment\Model\Config;

class Css extends Template
{
    /**
     * Constructor
     *
     * @param Template\Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        private Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Retrieves CSS based on the provided configuration path
     *
     * @return string
     */
    public function getCssByPath(): string
    {
        if (!$this->config->isEnabled()) {
            return '';
        }
        $path = (string)($this->getData('config_path') ?? '');
        if ($path === Config::XML_CHECKOUT_CSS) {
            return $this->config->getCheckoutCss();
        }
        if ($path === Config::XML_ACCOUNT_CSS) {
            return $this->config->getAccountCss();
        }
            
        return '';
    }
}
