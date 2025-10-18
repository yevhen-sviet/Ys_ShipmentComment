<?php
/**
 * Created by Yevhen Sviet
 */

declare(strict_types=1);

namespace Ys\ShipmentComment\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    /*   
    * Configuration XML paths 
    */
    public const XML_ENABLED         = 'shipping/ys_shipmentcomment/enabled';
    public const XML_CHECKOUT_CSS    = 'shipping/ys_shipmentcomment/checkout_css';
    public const XML_ACCOUNT_CSS     = 'shipping/ys_shipmentcomment/account_css';
    public const XML_REQUIRE_ENABLED = 'shipping/ys_shipmentcomment/require_enabled';
    public const XML_REQUIRED_LIST   = 'shipping/ys_shipmentcomment/required_methods';

    /*
    * Constructor
    *
    * @param ScopeConfigInterface $scopeConfig
    */
    public function __construct(private ScopeConfigInterface $scopeConfig) {}

    /*
    * Retrieves whether the shipment comment feature is enabled
    *
    * @return bool
    */
    public function isEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_ENABLED);
    }

    /*
    * Retrieves whether the shipment comment is required
    *
    * @return bool
    */
    public function isRequireEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_REQUIRE_ENABLED);
    }

    /*
    * Retrieves the list of shipping methods that require a shipment comment
    *
    * @return array
    */
    public function getRequiredMethods(): array
    {
        $raw = (string)($this->scopeConfig->getValue(self::XML_REQUIRED_LIST) ?? '');
        return array_filter(array_map('trim', explode(',', $raw)));
    }

    /*
    * Retrieves custom CSS for checkout page
    *
    * @return string
    */
    public function getCheckoutCss(): string
    {
        return (string)($this->scopeConfig->getValue(self::XML_CHECKOUT_CSS) ?? '');
    }

    /*
    * Retrieves custom CSS for account page
    *
    * @return string
    */
    public function getAccountCss(): string
    {
        return (string)($this->scopeConfig->getValue(self::XML_ACCOUNT_CSS) ?? '');
    }
}
