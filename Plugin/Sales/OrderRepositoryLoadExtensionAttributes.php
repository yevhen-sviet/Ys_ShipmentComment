<?php
/**
 * Created by Yevhen Sviet
 */

declare(strict_types=1);

namespace Ys\ShipmentComment\Plugin\Sales;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;

class OrderRepositoryLoadExtensionAttributes
{
    /**
    * Constructor
    *
    * @param OrderExtensionFactory $orderExtensionFactory
    */
    public function __construct(private OrderExtensionFactory $orderExtensionFactory) {}

    /*
    * After plugin for get method
    *
    * @param OrderRepositoryInterface $subject
    * @param OrderInterface $order
    * @return OrderInterface
    * @SuppressWarnings(PHPMD.UnusedFormalParameter)
    */
    public function afterGet(
        OrderRepositoryInterface $subject, 
        OrderInterface $order
        ): OrderInterface
    {
        $this->hydrateExtensionAttribute($order);
        return $order;
    }

    /*
    * After plugin for getList method
    *
    * @param OrderRepositoryInterface $subject
    * @param OrderSearchResultInterface $searchResult
    * @return OrderSearchResultInterface
    * @SuppressWarnings(PHPMD.UnusedFormalParameter)
    */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $searchResult
    ): OrderSearchResultInterface {
        foreach ($searchResult->getItems() as $order) {
            $this->hydrateExtensionAttribute($order);
        }
        return $searchResult;
    }

    /*
    * Hydrates the shipment comment extension attribute for the given order
    *
    * @param OrderInterface $order
    * @return void
    */
    private function hydrateExtensionAttribute(OrderInterface $order): void
    {
        $comment = (string)$order->getData('shipment_comment');
        $attributes = $order->getExtensionAttributes();
        if ($attributes === null) {
            $attributes = $this->orderExtensionFactory->create();
        }
        if (method_exists($attributes, 'setShipmentComment')) {
            $attributes->setShipmentComment($comment);
            $order->setExtensionAttributes($attributes);
        }
    }
}
