<?php
/**
 * Created by Yevhen Sviet
 */  

declare(strict_types=1);

namespace Ys\ShipmentComment\Block\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Sales\Api\Data\OrderInterface;

class Comment extends Template
{
    public function __construct(private Registry $registry) {}

    /**
     * Returns the current order if available
     */
    public function getOrder(): ?OrderInterface
    {
        $order = $this->registry->registry('current_order');
        return $order instanceof OrderInterface ? $order : null;
    }

    /**
     * Returns the shipment comment text
     */
    public function getComment(): string
    {
        $order = $this->getOrder();
        return $order ? (string)$order->getData('shipment_comment') : '';
    }
}
