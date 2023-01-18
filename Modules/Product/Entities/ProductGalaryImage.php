<?php

namespace Modules\Product\Entities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGalaryImage extends Model
{
    use HasFactory;
    protected $table = "product_galary_images";
    protected $guarded = ["id"];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id','id');
    }
}
