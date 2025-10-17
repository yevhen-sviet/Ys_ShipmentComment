<?php
/**
 * Created by Yevhen Sviet
 */

declare(strict_types=1);

namespace Ys\ShipmentComment\Plugin\Adminhtml;

use Magento\Sales\Block\Adminhtml\Order\AbstractOrder as AbstractOrderBlock;
use Ys\ShipmentComment\Block\Adminhtml\Order\Comment as CommentBlock;

class AppendAfterShippingInfo
{
    public function afterToHtml(AbstractOrderBlock $subject, string $result): string
    {
        $isShippingBlockByName = ($subject->getNameInLayout() === 'order_shipping_view');
        $isShippingBlockByTpl  = ($subject->getTemplate() === 'Magento_Shipping::order/view/info.phtml');

        if (!($isShippingBlockByName || $isShippingBlockByTpl)) {
            return $result;
        }

        $order = $subject->getOrder();
        if (!$order || $order->getIsVirtual() || !$order->getData('shipment_comment')) {
            return $result;
        }

        $layout = $subject->getLayout();
        if (!$layout) {
            return $result;
        }

        /** @var CommentBlock $block */
        $block = $layout->createBlock(CommentBlock::class);
        if (!$block) {
            return $result;
        }

        $block->setTemplate('Ys_ShipmentComment::order/comment.phtml');
        $block->setData('order', $order);

        $html = trim($block->toHtml());
        if ($html === '') {
            return $result;
        }

        return $result . $html;
    }
}
