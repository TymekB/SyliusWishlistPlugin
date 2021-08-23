<?php

declare(strict_types=1);

namespace BitBag\SyliusWishlistPlugin\Controller\Action\ApiPlatform;

use BitBag\SyliusWishlistPlugin\Command\Wishlist\RemoveProductVariantFromWishlist;
use BitBag\SyliusWishlistPlugin\Resolver\WishlistTokenResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

final class RemoveProductVariantFromWishlistAction
{
    private MessageBusInterface $messageBus;

    private WishlistTokenResolver $wishlistTokenResolver;

    public function __construct(MessageBusInterface $messageBus, WishlistTokenResolver $wishlistTokenResolver)
    {
        $this->messageBus = $messageBus;
        $this->wishlistTokenResolver = $wishlistTokenResolver;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $tokenIdentifier = $this->wishlistTokenResolver->resolveToken();
        $wishlistToken = (string)$request->attributes->get($tokenIdentifier);
        $productVariantId = (int)$request->attributes->get('productVariantId');

        $removeProductVariantFromWishlist = new RemoveProductVariantFromWishlist($productVariantId, $wishlistToken);
        $this->messageBus->dispatch($removeProductVariantFromWishlist);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
