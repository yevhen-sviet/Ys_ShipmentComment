<?php
/**
 * Created by Yevhen Sviet
 */

declare(strict_types=1);

namespace Ys\ShipmentComment\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class OrderShipmentComment implements ResolverInterface
{
    /*
    * Resolver to get shipment comment from order
    *
    * @param mixed $field
    * @param mixed $context
    * @param ResolveInfo $info
    * @param array|null $value
    * @param array|null $args
    * @return string|null
    */
    public function resolve(
        $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null
    ) {
        if (!isset($value['model'])) return null;
        $order = $value['model'];
        return (string)$order->getData('shipment_comment');
    }
}
