<?php

use App\Models\MediaManager;
use App\Traits\ImageStore;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductGalaryImage;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AddPreviusImgToMediaManagers extends Migration
{
    use ImageStore;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('product_galary_images')){
            if(!Schema::hasColumn('products', 'media_ids')){
                Schema::table('products', function (Blueprint $table) {
                    $table->string('media_ids')->nullable()->after('thumbnail_image_source');
                });
            }
            if(!Schema::hasColumn('product_galary_images', 'media_id')){
                Schema::table('product_galary_images', function (Blueprint $table) {
                    $table->unsignedBigInteger('media_id')->nullable()->after('images_source');
                });
            }

            $prev_galary_images = ProductGalaryImage::with('product')->get()->groupBy('product_id');

            foreach($prev_galary_images as $product_id => $images){
                $media_ids = '';
                $numItems = $images->count();
                $i = 0;
                foreach($images as $image){
                    if(strpos($image->images_source, 'amazonaws.com') != false){
                        $file_info = $this->saveGalleryImgFromPrev($image->images_source);
                    }else{
                        if(File::exists(asset_path($image->images_source))){
                            $file_info = $this->saveGalleryImgFromPrev(asset_path($image->images_source));
                        }else{
                            $file_info = null;
                        }
                    }
                    if($file_info != null){
                        $file_info['user_id'] = @$image->product->created_by;
                        $sql  = $file_info;
                        $media = MediaManager::create($sql);
                        $image->update([
                            'media_id' => $media->id
                        ]);
                        if(++$i === $numItems) {
                            $media_ids .= $media->id;
                        }else{
                            $media_ids .= $media->id.',';
                        }
                    }
                }
                $product = Product::find($product_id);
                if($product){
                    $product->update([
                        'media_ids' => $media_ids
                    ]);
                }
            }
        }
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('products', 'media_ids')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('media_ids');
            });
        }
        if(Schema::hasColumn('product_galary_images', 'media_id')){
            Schema::table('product_galary_images', function (Blueprint $table) {
                $table->dropColumn('media_id');
            });
        }
    }
}
