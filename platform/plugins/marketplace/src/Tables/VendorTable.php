<?php

namespace Botble\Marketplace\Tables;

use Botble\Ecommerce\Tables\CustomerTable;

class VendorTable extends CustomerTable
{
    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()
            ->select([
                'id',
                'name',
                'email',
                'avatar',
                'created_at',
                'status',
                'confirmed_at',
            ])
            ->where('is_vendor', true);

        return $this->applyScopes($query);
    }
}
