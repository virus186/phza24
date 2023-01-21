<?php

namespace Botble\Marketplace\Repositories\Eloquent;

use Botble\Marketplace\Models\CategoryCommission;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class StoreRepository extends RepositoriesAbstract implements StoreInterface
{
    /**
     * @param $data
     * @return array
     */
    public function handleCommissionEachCategory($data): array
    {
        $commissions = [];
        CategoryCommission::truncate();
        foreach ($data as $datum) {
            if (!$datum['categories']) {
                continue;
            }

            $categories = json_decode($datum['categories'], true);

            if (!is_array($categories) || !count($categories)) {
                continue;
            }

            foreach ($categories as $category) {
                $commission = CategoryCommission::firstOrNew([
                    'product_category_id' => $category['id'],
                ]);

                if (!$commission) {
                    continue;
                }

                $commission->commission_percentage = $datum['commission_fee'];
                $commission->save();
                $commissions[] = $commission;
            }
        }

        return $commissions;
    }

    /**
     * @return array
     */
    public function getCommissionEachCategory(): array
    {
        $commissions = CategoryCommission::with(['category'])->get();
        $data = [];
        foreach ($commissions as $commission) {
            if (!$commission->category) {
                continue;
            }

            $data[$commission->commission_percentage]['commission_fee'] = $commission->commission_percentage;
            $data[$commission->commission_percentage]['categories'][] = [
                'id' => $commission->product_category_id,
                'value' => $commission->category->name,
            ];
        }

        return $data;
    }
}
