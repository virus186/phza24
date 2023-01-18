<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\ProductGalaryImage;

class MediaManager extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function used_media(){
        return $this->hasMany(UsedMedia::class, 'media_id', 'id');
    }
    public function gallery_images(){
        return $this->hasMany(ProductGalaryImage::class, 'media_id', 'id');
    }
}
