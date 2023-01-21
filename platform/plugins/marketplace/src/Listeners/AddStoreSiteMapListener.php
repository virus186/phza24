<?php

namespace Botble\Marketplace\Listeners;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;
use SiteMapManager;

class AddStoreSiteMapListener
{
    /**
     * @var StoreInterface
     */
    protected $storeRepository;

    /**
     * @param StoreInterface $storeRepository
     */
    public function __construct(StoreInterface $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        SiteMapManager::add(route('public.stores'), '2022-09-14 00:00:00', '1', 'monthly');

        $stores = $this->storeRepository
            ->getModel()
            ->with('slugable')
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($stores as $store) {
            if (!$store->slugable) {
                continue;
            }

            SiteMapManager::add($store->url, $store->updated_at, '0.8');
        }
    }
}
