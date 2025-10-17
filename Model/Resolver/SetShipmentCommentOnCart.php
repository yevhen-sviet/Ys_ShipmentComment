<?php
/**
 * Created by Yevhen Sviet
 */

declare(strict_types=1);

namespace Ys\ShipmentComment\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Ys\ShipmentComment\Model\Config;

class SetShipmentCommentOnCart implements ResolverInterface
{
    public function __construct(
        private GetCartForUser $getCartForUser,
        private CartRepositoryInterface $cartRepository,
        private Config $config
    ) {}

    public function resolve(
        $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null
    ) {
        if (!$this->config->isEnabled()) {
            throw new LocalizedException(__('Shipment comment feature is disabled.'));
        }

        $input = $args['input'] ?? [];
        $maskedCartId = (string)($input['cart_id'] ?? '');
        $comment = trim((string)($input['shipment_comment'] ?? ''));

        if ($maskedCartId === '') {
            throw new LocalizedException(__('Required parameter "cart_id" is missing.'));
        }

        $cart = $this->getCartForUser->execute($maskedCartId, (int)$context->getUserId());
        $quote = $cart->getModel();

        $quote->setData('shipment_comment', $comment);
        $this->cartRepository->save($quote);

        return ['cart' => ['model' => $quote]];
    }
}
