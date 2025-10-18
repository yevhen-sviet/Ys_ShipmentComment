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
    /**
     * Constructor
     *
     * @param Config $config
     */
    public function __construct(private Config $config) {}

    /*
    * After plugin for setTemplateVars method to add shipment comment to email template variables
    *
    * @param SenderBuilder $subject
    * @param SenderBuilder $result
    * @return SenderBuilder
    * @SuppressWarnings(PHPMD.UnusedFormalParameter)
    */
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
