<?php
declare(strict_types=1);

namespace Ys\ShipmentComment\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Ys\ShipmentComment\Helper\Data as Helper;

class CopyCommentToOrder implements ObserverInterface
{
    public function __construct(private Helper $helper) {}

    public function execute(Observer $observer): void
    {
        if (!$this->helper->isEnabled()) {
            return;
        }

        $quote = $observer->getEvent()->getQuote();
        $order = $observer->getEvent()->getOrder();
        if (!$quote || !$order) return;

        $comment = (string)$quote->getData('shipment_comment');
        if ($comment !== '') {
            $order->setData('shipment_comment', $comment);
        }
    }
}
