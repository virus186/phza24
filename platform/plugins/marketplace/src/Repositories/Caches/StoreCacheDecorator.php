<?php

namespace Botble\Marketplace\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;

class StoreCacheDecorator extends CacheAbstractDecorator implements StoreInterface
{
    public function handleCommissionEachCategory($data): array
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getCommissionEachCategory(): array
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
