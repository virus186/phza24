<?php

namespace Modules\Product\Services;

use App\Models\MediaManager;
use App\Models\UsedMedia;
use App\Traits\GenerateSlug;
use Illuminate\Support\Facades\Validator;
use \Modules\Product\Repositories\ProductRepository;
use App\Traits\ImageStore;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductGalaryImage;
use Modules\Product\Entities\ProductSku;

class ProductService
{
    use ImageStore;
    use GenerateSlug;
    protected $productRepository;

    public function __construct(ProductRepository  $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function all()
    {
        return $this->productRepository->getAll();
    }

    public function allbyPaginate()
    {
        return $this->productRepository->allbyPaginate();
    }

    public function getAllForEdit($id){
        return $this->productRepository->getAllForEdit($id);
    }

    public function getAllSKU(){
        return $this->productRepository->getAllSKU();
    }

    public function create($data)
    {
        $thumbnail_image = null;
        $galary_image = [];
        $media_ids = '';
        if(isset($data['images'])){
            $numItems = count($data['images']);
            $i = 0;
            foreach($data['images'] as $key => $image){
                $media_img = MediaManager::find($image);
                if($media_img){
                    if(++$i === $numItems) {
                        $media_ids .= $media_img->id;
                    }else{
                        $media_ids .= $media_img->id.',';
                    }
                    if($media_img->storage == 'local'){
                        $file = asset_path($media_img->file_name);
                    }else{
                        $file = $media_img->file_name;
                    }
                    if($key == 0){
                        $thumbnail_image = ImageStore::saveImage($file, 300, 300);
                    }
                    $galary_image[] = ImageStore::saveImage($file,1000,1000);
                }
            }
            
        }
        $data['galary_image'] = $galary_image;
        $data['thumbnail_image_source'] = $thumbnail_image;
        $data['media_ids'] = $media_ids;
        
        if (!empty($data['pdf_file'])) {
            $name = uniqid() . $data['pdf_file']->getClientOriginalName();
            $data['pdf_file']->move(public_path() . '/uploads/product_pdf/', $name);
            $data['pdf'] = '/uploads/product/' . $name;
        }
        
        if($data['is_physical'] == 0 && isset($data['digital_file'])) {
            if ($data['product_type'] == 2) {
                foreach ($data['digital_file'] as $key => $file) {
                    $name = uniqid() . $file->getClientOriginalName();
                    $file->move(public_path() . '/uploads/digital_file/', $name);
                    $data['file_source'][$key] = '/uploads/digital_file/' . $name;
                }
            }else {
                $name = uniqid() . $data['digital_file']->getClientOriginalName();
                $data['digital_file']->move(public_path() . '/uploads/digital_file/', $name);
                $data['file_source'] = '/uploads/digital_file/' . $name;
            }
        }
        if (isset($data['meta_image'])) {
            $media_img = MediaManager::find($data['meta_image']);
            if($media_img){
                if($media_img->storage == 'local'){
                    $file = asset_path($media_img->file_name);
                }else{
                    $file = $media_img->file_name;
                }
                $meta_image = ImageStore::saveImage($file,300,300);
                $data['meta_image'] = $meta_image;
                $data['meta_image_id'] = $media_img->id;
            }
        }

        $name_exsist = Product::where('product_name', $data['product_name'])->where('created_by', getParentSellerId())->first();
        
        $data['slug'] = $this->productSlug($data['product_name']);
        return $this->productRepository->create($data);
    }

    public function findById($id)
    {
        return $this->productRepository->find($id);
    }

    public function findProductSkuById($id)
    {
        return $this->productRepository->findProductSkuById($id);
    }

    public function update($data, $id)
    {
        $product = $this->findById($id);

        if(!isset($data['images'])){
            $data['images'] = [];
        }
        $thumbnail_image = null;
        $galary_image = [];
        $media_ids = '';
        if($product->media_ids){
            $prev_media_ids = explode(',',$product->media_ids);
        }else{
            $prev_media_ids = [];
        }
        $deleted_image_ids = [];
        foreach($prev_media_ids as $key => $value){
            if(!in_array($value, $data['images'])){
                $deleted_image_ids[] = $value;
            }
        }
        
        $deleted_images = $product->gallary_images->whereIn('media_id', $deleted_image_ids);
        foreach($deleted_images as $dl_img){
            $this->deleteImage($dl_img->images_source);
            $dl_img->delete();
        }
        
        $numItems = count($data['images']);
        $i = 0;
        foreach($data['images'] as $key => $image){
            $galary_images = $product->gallary_images;
            $newData = $galary_images->where('media_id', $image)->first();
            if(++$i === $numItems) {
                $media_ids .= $image;
            }else{
                $media_ids .= $image.',';
            }
            if(!$newData){
                $media_img = MediaManager::find($image);
                if($media_img){
                    if($media_img->storage == 'local'){
                        $file = asset_path($media_img->file_name);
                    }else{
                        $file = $media_img->file_name;
                    }
                    $galary_image = ImageStore::saveImage($file,1000,1000);
                }
                ProductGalaryImage::create([
                    'product_id' => $product->id,
                    'images_source' => $galary_image,
                    'media_id' => $media_img->id
                ]);
            }
        }
        if(count($data['images']) > 0){
            if(count($prev_media_ids) < 1 || count($prev_media_ids) > 0 && $prev_media_ids[0] != $data['images'][0]){
                $this->deleteImage($product->thumbnail_image_source);
                $media_img = MediaManager::find($data['images'][0]);
                if($media_img->storage == 'local'){
                    $file = asset_path($media_img->file_name);
                }else{
                    $file = $media_img->file_name;
                }
                $thumbnail_image = ImageStore::saveImage($file, 300, 300);
                $data['thumbnail_image_source'] = $thumbnail_image;
            }
        }else{
            $this->deleteImage($product->thumbnail_image_source);
            $data['thumbnail_image_source'] = null;
        }
        

        
        $data['media_ids'] = $media_ids;

        

        if (!empty($data['pdf_file'])) {
            $name = uniqid() . $data['pdf_file']->getClientOriginalName();
            $data['pdf_file']->move(public_path() . '/uploads/product_pdf/', $name);
            $data['pdf'] = '/uploads/product/' . $name;
        }
        
        
        if (isset($data['meta_image']) && $data['meta_image'] != @$product->meta_image_media->media_id) {
            if(@$product->meta_image != null){
                ImageStore::deleteImage($product->meta_image);
            }
            $media_img = MediaManager::find($data['meta_image']);
            if($media_img->storage == 'local'){
                $file = asset_path($media_img->file_name);
            }else{
                $file = $media_img->file_name;
            }
            $meta_image = ImageStore::saveImage($file,300,300);
            $data['meta_image'] = $meta_image;
            $prev_meta = UsedMedia::where('usable_id', $product->id)->where('usable_type', get_class($product))->where('used_for', 'meta_image')->first();
            if($prev_meta){
                $prev_meta->update([
                    'media_id' => $media_img->id
                ]);
            }else{
                UsedMedia::create([
                    'media_id' => $media_img->id,
                    'usable_id' => $product->id,
                    'usable_type' => get_class($product),
                    'used_for' => 'meta_image'
                ]);
            }
        }
        else{
            if($product->meta_image_media != null && !isset($data['meta_image'])){
                $product->meta_image_media->delete();
                $this->deleteImage($product->meta_image);
                $data['meta_image'] = null;
            }else{
                $data['meta_image'] = $product->meta_image;
            }
        }
        return $this->productRepository->update($data, $id);
    }

    public function deleteById($id)
    {
        return $this->productRepository->delete($id);
    }

    public function metaImgDeleteById($id){
        $product = $this->productRepository->find($id);
        ImageStore::deleteImage($product->meta_image);
        $product->update([
            'meta_image' => null
        ]);
        return true;
    }

    public function getRequestProduct(){
        return $this->productRepository->getRequestProduct();
    }
    public function productApproved($data){
        return $this->productRepository->productApproved($data);
    }

    public function updateRecentViewedConfig($data)
    {
        return $this->productRepository->updateRecentViewedConfig($data);
    }

    public function csvUploadProduct($data)
    {
        return $this->productRepository->csvUploadProduct($data);
    }
    public function getProduct()
    {
        return $this->productRepository->getProduct();
    }

    public function getByAjax($search){
        return $this->productRepository->getByAjax($search);
    }
    public function getSellerProductByAjax($search){
        return $this->productRepository->getSellerProductByAjax($search);
    }

    public function updateSkuByID($data){
        if(isset($data['variant_image'])){
            $sku = ProductSku::find($data['id']);
            ImageStore::deleteImage($sku->variant_image);
            $data['variant_image'] = ImageStore::saveImage($data['variant_image'],165,165);
        }
        return $this->productRepository->updateSkuByID($data);
    }

    public function getFilterdProduct($table){
        return $this->productRepository->getFilterdProduct($table);
    }
    public function getSellerProduct(){
        return $this->productRepository->getSellerProduct();
    }
    public function related_product($data){
        return $this->productRepository->related_product($data);
    }
    public function upsale_product($data){
        return $this->productRepository->upsale_product($data);
    }
    public function crosssale_product($data){
        return $this->productRepository->crosssale_product($data);
    }
}

