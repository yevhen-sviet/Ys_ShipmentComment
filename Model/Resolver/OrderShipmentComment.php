<?php
declare(strict_types=1);

namespace Ys\ShipmentComment\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class OrderShipmentComment implements ResolverInterface
{
    public function resolve(
        $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,   // ✅ explicitly nullable
        ?array $args = null     // ✅ explicitly nullable
    ) {
        if (!isset($value['model'])) return null;
        $order = $value['model'];
        return (string)$order->getData('shipment_comment');
    }
}
