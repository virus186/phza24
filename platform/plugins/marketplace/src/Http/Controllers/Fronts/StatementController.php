<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Marketplace\Repositories\Interfaces\RevenueInterface;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;
use Botble\Marketplace\Tables\StoreRevenueTable;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use MarketplaceHelper;
use Throwable;

class StatementController extends BaseController
{
    /**
     * @var StoreInterface
     */
    protected $storeRepository;

    /**
     * @var RevenueInterface
     */
    protected $revenueRepository;

    /**
     * @param StoreInterface $storeRepository
     * @param RevenueInterface $revenueRepository
     */
    public function __construct(StoreInterface $storeRepository, RevenueInterface $revenueRepository)
    {
        $this->storeRepository = $storeRepository;
        $this->revenueRepository = $revenueRepository;
    }

    /**
     * @param StoreRevenueTable $table
     * @param Request $request
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function index(StoreRevenueTable $table, Request $request)
    {
        page_title()->setTitle(__('Statements'));

        $request->route()->setParameter('id', auth('customer')->id());

        $table
            ->setType(TableAbstract::TABLE_TYPE_ADVANCED)
            ->setView('core/table::table');

        return $table->render(MarketplaceHelper::viewPath('dashboard.table.base'));
    }
}
