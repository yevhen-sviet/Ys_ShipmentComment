<?php
declare(strict_types=1);

namespace Ys\ShipmentComment\Plugin\Sales;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;

class OrderRepositoryLoadExtensionAttributes
{
    public function __construct(private OrderExtensionFactory $orderExtensionFactory) {}

    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order): OrderInterface
    {
        $this->hydrateExtensionAttribute($order);
        return $order;
    }

    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $searchResult
    ): OrderSearchResultInterface {
        foreach ($searchResult->getItems() as $order) {
            $this->hydrateExtensionAttribute($order);
        }
        return $searchResult;
    }

    private function hydrateExtensionAttribute(OrderInterface $order): void
    {
        $comment = (string)$order->getData('shipment_comment');
        $$attributes = $order->getExtensionAttributes();
        if ($attributes === null) {
            $attributes = $this->orderExtensionFactory->create();
        }
        if (method_exists($attributes, 'setShipmentComment')) {
            $attributes->setShipmentComment($comment);
            $order->setExtensionAttributes($attributes);
        }
    }
}
