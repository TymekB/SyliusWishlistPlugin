<?php

declare(strict_types=1);

namespace BitBag\SyliusWishlistPlugin\Controller\Action\ApiPlatform;

use BitBag\SyliusWishlistPlugin\Command\Wishlist\RemoveProductFromWishlist;
use BitBag\SyliusWishlistPlugin\Resolver\WishlistTokenResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

final class RemoveProductFromWishlistAction
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
        $wishlistIdentifier = $this->wishlistTokenResolver->resolveToken();

        $wishlistToken = (string)$request->attributes->get($wishlistIdentifier);
        $productId = (int)$request->attributes->get('productId');

        $removeProductFromWishlist = new RemoveProductFromWishlist($productId, $wishlistToken);
        $this->messageBus->dispatch($removeProductFromWishlist);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
