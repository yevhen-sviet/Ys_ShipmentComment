<?php
/**
 * Created by Yevhen Sviet
 */

declare(strict_types=1);

namespace Ys\ShipmentComment\Plugin\Email;

use Magento\Sales\Model\Order\Email\SenderBuilder;
use Ys\ShipmentComment\Model\Config;

class AddShipmentCommentToEmail
{
    public function __construct(private Config $config) {}

    public function afterSetTemplateVars(SenderBuilder $subject, SenderBuilder $result)
    {
        if (!$this->config->isEnabled()) {
            return $result;
        }

        $reflection = new \ReflectionClass($subject);
        $prop = $reflection->getProperty('templateVars');
        $prop->setAccessible(true);
        $vars = $prop->getValue($subject) ?? [];

        $order = $vars['order'] ?? null;
        $comment = '';
        if ($order && $order->getEntityId()) {
            $comment = (string)$order->getData('shipment_comment');
        }

        $vars['shipment_comment'] = $comment;
        $prop->setValue($subject, $vars);

        return $result;
    }
}
