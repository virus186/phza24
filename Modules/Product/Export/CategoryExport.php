<?php

namespace Modules\Product\Export;

use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;


class CategoryExport implements FromCollection, WithHeadings
{
    use Exportable;
    public function collection()
    {
        return DB::table('categories')->select('id', 'name','slug', 'parent_id','depth_level', 'total_sale','avg_rating','commission_rate')->get();
    }
    public function headings(): array
    {
        return [
            'id',
            'name',
            'slug',
            'parent_id',
            'depth_level',
            'total_sale',
            'avg_rating',
            'commission_rate'
        ];
    }
}
