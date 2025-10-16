<?php
declare(strict_types=1);

namespace Ys\ShipmentComment\Plugin\Checkout;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Framework\Exception\InputException;
use Magento\Quote\Api\CartRepositoryInterface;
use Ys\ShipmentComment\Model\Config;

class ShippingInformationManagementPlugin
{
    public function __construct(
        private CartRepositoryInterface $quoteRepository,
        private Config $config
    ) {}

    public function beforeSaveAddressInformation(
        ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        if (!$this->config->isEnabled()) {
            return [$cartId, $addressInformation];
        }

        $carrier = (string)($addressInformation->getShippingCarrierCode() ?? '');
        $method  = (string)($addressInformation->getShippingMethodCode() ?? '');
        $key     = $carrier . '_' . $method;

        $selected = in_array($key, $this->config->getRequiredMethods(), true);
        $mustRequire = $selected && $this->config->isRequireEnabled();

        $ext = $addressInformation->getExtensionAttributes();
        $comment = $ext ? (string)$ext->getShipmentComment() : '';

        if ($mustRequire && $comment === '') {
            throw new InputException(__('Shipment comment is required for the selected shipping method.'));
        }

        $quote = $this->quoteRepository->getActive((int)$cartId);
        if ($selected && $comment !== '') {
            $quote->setData('shipment_comment', $comment);
            $this->quoteRepository->save($quote);
        } elseif (!$selected) {
            // clear previous comment when switching to non-selected method
            $quote->setData('shipment_comment', null);
            $this->quoteRepository->save($quote);
        }

        return [$cartId, $addressInformation];
    }
}
