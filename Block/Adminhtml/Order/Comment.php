<?php
/**
 * Created by Yevhen Sviet
 */

declare(strict_types=1);

namespace Ys\ShipmentComment\Block\Adminhtml\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Sales\Api\Data\OrderInterface;

class Comment extends Template
{
    public function getComment(): string
    {
        $order = $this->getData('order');
        return $order ? (string)$order->getData('shipment_comment') : '';
    }
}
