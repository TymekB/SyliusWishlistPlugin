<?php

declare(strict_types=1);

namespace BitBag\SyliusWishlistPlugin\Resolver;

use Jean85\PrettyVersions;

final class WishlistTokenResolver
{
    private PrettyVersions $versions;

    public function __construct(PrettyVersions $versions)
    {
        $this->versions = $versions;
    }

    public function resolveToken(): string
    {
        $syliusVersion = $this->versions::getVersion('sylius/sylius');

        if(version_compare($syliusVersion->getShortVersion(), 'v1.10.0', '>=')) {
            return 'token';
        }

        return 'id';
    }

}
