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
    public function __construct(
        Template\Context $context,
        private Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Returns the current order if available
     */
    public function getOrder(): ?OrderInterface
    {
        if ($this->hasData('order')) {
            $order = $this->getData('order');
            return $order instanceof OrderInterface ? $order : null;
        }

        $parent = $this->getParentBlock();
        if ($parent && method_exists($parent, 'getOrder')) {
            $order = $parent->getOrder();
            return $order instanceof OrderInterface ? $order : null;
        }

        $order = $this->registry->registry('current_order');
        return $order instanceof OrderInterface ? $order : null;
    }

    /**
     * Returns the shipment comment text (or empty string)
     */
    public function getComment(): string
    {
        $order = $this->getOrder();
        return $order ? (string)$order->getData('shipment_comment') : '';
    }
}
