<?php
declare(strict_types=1);

namespace Ys\ShipmentComment\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    public const XML_ENABLED         = 'shipping/ys_shipmentcomment/enabled';
    public const XML_CHECKOUT_CSS    = 'shipping/ys_shipmentcomment/checkout_css';
    public const XML_ACCOUNT_CSS     = 'shipping/ys_shipmentcomment/account_css';
    public const XML_REQUIRE_ENABLED = 'shipping/ys_shipmentcomment/require_enabled';
    public const XML_REQUIRED_LIST   = 'shipping/ys_shipmentcomment/required_methods';

    public function __construct(private ScopeConfigInterface $scopeConfig) {}

    public function isEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_ENABLED);
    }

    public function isRequireEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_REQUIRE_ENABLED);
    }

    public function getRequiredMethods(): array
    {
        $raw = (string)($this->scopeConfig->getValue(self::XML_REQUIRED_LIST) ?? '');
        return array_filter(array_map('trim', explode(',', $raw)));
    }

    public function getCheckoutCss(): string
    {
        return (string)($this->scopeConfig->getValue(self::XML_CHECKOUT_CSS) ?? '');
    }

    public function getAccountCss(): string
    {
        return (string)($this->scopeConfig->getValue(self::XML_ACCOUNT_CSS) ?? '');
    }
}
