<?php
/**
 * Created by Yevhen Sviet
 */

declare(strict_types=1);

namespace Ys\ShipmentComment\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class CartShipmentComment implements ResolverInterface
{
    /*
    * Resolver to get shipment comment from cart
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
        $quote = $value['model'];
        return (string)$quote->getData('shipment_comment');
    }
}
