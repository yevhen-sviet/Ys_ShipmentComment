<?php
declare(strict_types=1);

namespace Ys\ShipmentComment\Block\Adminhtml\Order;

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

    public function getOrder(): ?OrderInterface
    {
        if ($this->hasData('order') && $this->getData('order') instanceof OrderInterface) {
            return $this->getData('order');
        }
        $parent = $this->getParentBlock();
        if ($parent && method_exists($parent, 'getOrder')) {
            $o = $parent->getOrder();
            return $o instanceof OrderInterface ? $o : null;
        }
        $order = $this->registry->registry('current_order');
        return $order instanceof OrderInterface ? $order : null;
    }

    public function getComment(): string
    {
        $order = $this->getOrder();
        return $order ? (string)$order->getData('shipment_comment') : '';
    }
}
