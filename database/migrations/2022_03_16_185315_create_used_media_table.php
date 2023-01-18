<?php

use App\Models\MediaManager;
use App\Models\UsedMedia;
use App\Models\User;
use App\Traits\ImageStore;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Modules\Product\Entities\Product;
use Modules\RolePermission\Entities\Permission;
use Modules\Seller\Entities\SellerProduct;

class CreateUsedMediaTable extends Migration
{
    use ImageStore;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('used_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('media_id');
            $table->unsignedBigInteger('usable_id');
            $table->string('usable_type');
            $table->string('used_for');
            $table->timestamps();
        });
        $products = Product::where('meta_image', '!=', null)->get();
        foreach($products as $product){
            if(strpos($product->meta_image, 'amazonaws.com') != false){
                $file_info = $this->saveGalleryImgFromPrev($product->meta_image);
            }else{
                if(File::exists(asset_path($product->meta_image))){
                    $file_info = $this->saveGalleryImgFromPrev(asset_path($product->meta_image));
                }else{
                    $file_info = null;
                }
            }
            if($file_info != null){
                $file_info['user_id'] = @$product->product->created_by??1;
                $sql  = $file_info;
                $media = MediaManager::create($sql);
                UsedMedia::create([
                    'media_id' => $media->id,
                    'usable_id' => $product->id,
                    'usable_type' => get_class($product),
                    'used_for' => 'meta_image'
                ]);
            }
        }

        $seller_products = SellerProduct::where('thum_img', '!=', null)->get();
        foreach($seller_products as $key => $seller_product){

            if(strpos($seller_product->thum_img, 'amazonaws.com') != false){
                $file_info = $this->saveGalleryImgFromPrev($seller_product->thum_img);
            }else{
                if(File::exists(asset_path($seller_product->thum_img))){
                    $file_info = $this->saveGalleryImgFromPrev(asset_path($seller_product->thum_img));
                }else{
                    $file_info = null;
                }
            }
            if($file_info != null){
                $file_info['user_id'] = $seller_product->user_id;
                $sql  = $file_info;
                $media = MediaManager::create($sql);
                UsedMedia::create([
                    'media_id' => $media->id,
                    'usable_id' => $seller_product->id,
                    'usable_type' => get_class($seller_product),
                    'used_for' => 'thumb_image'
                ]);
            }
        }

        if(Schema::hasTable('permissions')){
            $sql = [
                //configuration
                ['id' => 705, 'module_id' => 45, 'parent_id' => null, 'name' => 'Media Manager', 'route' => 'media-manager', 'type' => 1 ],
                ['id' => 706, 'module_id' => 45, 'parent_id' => 705, 'name' => 'All Upload Files', 'route' => 'media-manager.upload_files', 'type' => 2 ],
                ['id' => 707, 'module_id' => 45, 'parent_id' => 705, 'name' => 'New Upload', 'route' => 'media-manager.new-upload', 'type' => 2 ],
                ['id' => 708, 'module_id' => 45, 'parent_id' => 705, 'name' => 'Delete', 'route' => 'media-manager.delete_media_file', 'type' => 2 ]
            ];
            try{
                DB::table('permissions')->insert($sql);
            }catch(Exception $e){

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
        Schema::dropIfExists('used_media');

        $ids = Permission::where('module_id', 45)->pluck('id')->toArray();
        Permission::destroy($ids);
    }
}
