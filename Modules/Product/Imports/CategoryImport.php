<?php

namespace Modules\Product\Imports;
use Modules\Product\Entities\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CategoryImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Category([
            'id'     => $row['id'],
            'name'    => $row['name'],
            'slug'    => $row['slug'],
            'parent_id'    => !empty($row['parent_id'])?$row['parent_id']:0,
            'depth_level'    => $row['depth_level'],
            'total_sale'    => !empty($row['total_sale'])?$row['total_sale']:0,
            'avg_rating'    => !empty($row['avg_rating'])?$row['avg_rating']:0,
            'commission_rate'    => $row['commission_rate'],
        ]);
    }
}
