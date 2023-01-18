<?php

namespace Modules\Seller\Services;

use App\Models\MediaManager;
use App\Models\UsedMedia;
use Illuminate\Support\Facades\Validator;
use Modules\Seller\Repositories\ProductRepository;
use App\Traits\ImageStore;

class ProductService
{
    use ImageStore;
    protected $productRepository;

    public function __construct(ProductRepository $productRepository){
        $this->productRepository = $productRepository;
    }

    public function getAll(){
        return $this->productRepository->getAll();
    }

    public function getAllSellerProduct(){
        return $this->productRepository->getAllSellerProduct();
    }

    public function getRecomandedProduct(){
        return $this->productRepository->getRecomandedProduct();
    }

    public function getTopPicks(){
        return $this->productRepository->getTopPicks();
    }

    public function getSellerProductById($id){
        return $this->productRepository->getSellerProductById($id);
    }

    public function getFilterdProduct($data){
        return $this->productRepository->getFilterdProduct($data);
    }

    public function getMyProducts(){
        return $this->productRepository->getMyProducts();
    }

    public function getAllProduct(){
        return $this->productRepository->getAllProduct();
    }
    public function getAllMyProduct(){
        return $this->productRepository->getAllMyProduct();
    }
    public function getProductOfOtherSeller(){
        return $this->productRepository->getProductOfOtherSeller();
    }

    public function getProduct($id){
        return $this->productRepository->getProduct($id);
    }

    public function stockManageStatus($data){
        return $this->productRepository->stockManageStatus($data);
    }

    public function store($data){
        if(isset($data['thumbnail_image'])){
            $media_img = MediaManager::find($data['thumbnail_image']);
            if($media_img){
                if($media_img->storage == 'local'){
                    $file = asset_path($media_img->file_name);
                }else{
                    $file = $media_img->file_name;
                }
                $thumbnail_image = ImageStore::saveImage($file,300,300);
                $data['thum_img_src'] = $thumbnail_image;
                $data['thumb_image_id'] = $media_img->id;
            }
            
            // $thumbnail_image = ImageStore::saveImage($data['thumbnail_image'], 165, 165);
            // $data['thum_img_src'] = $thumbnail_image;
        }

        return $this->productRepository->store($data);
    }

    public function findById($id){
        return $this->productRepository->findById($id);
    }

    public function findBySellerProductId($id){
        return $this->productRepository->findBySellerProductId($id);
    }

    public function deleteById($id){
        return $this->productRepository->deleteById($id);
    }

    public function update($data, $id){
        $product = $this->getSellerProductById($id);
        if(isset($data['thumbnail_image']) && @$product->thumb_image_media->media_id != $data['thumbnail_image']){
            if($product->thum_img){
                ImageStore::deleteImage($product->thum_img);
            }
            $media_img = MediaManager::find($data['thumbnail_image']);
            if($media_img->storage == 'local'){
                $file = asset_path($media_img->file_name);
            }else{
                $file = $media_img->file_name;
            }
            $thumbnail_image = ImageStore::saveImage($file,300,300);
            $data['thum_img_src'] = $thumbnail_image;
            $prev_meta = UsedMedia::where('usable_id', $product->id)->where('usable_type', get_class($product))->where('used_for', 'thumb_image')->first();
            if($prev_meta){
                $prev_meta->update([
                    'media_id' => $media_img->id
                ]);
            }else{
                UsedMedia::create([
                    'media_id' => $media_img->id,
                    'usable_id' => $product->id,
                    'usable_type' => get_class($product),
                    'used_for' => 'thumb_image'
                ]);
            }
        }elseif(!isset($data['thumbnail_image']) && @$product->thumb_image_media ==  null && $product->thum_img != null){
            if(strpos($product->thum_img, 'amazonaws.com') != false){
                $file_info = $this->saveGalleryImgFromPrev($product->thum_img);
            }else{
                $file_info = $this->saveGalleryImgFromPrev(asset_path($product->thum_img));
            }
            $file_info['user_id'] = $product->user_id;
            $sql  = $file_info;
            $media = MediaManager::create($sql);
            UsedMedia::create([
                'media_id' => $media->id,
                'usable_id' => $product->id,
                'usable_type' => get_class($product),
                'used_for' => 'thumb_image'
            ]);
        }elseif(!isset($data['thumbnail_image']) && @$product->thumb_image_media !=  null){
            $this->deleteImage($product->thum_img);
            $product->thumb_image_media->delete();
            $product->update([
                'thum_img' => null
            ]);
        }
        return $this->productRepository->update($data, $id);
    }

    public function statusChange($data, $id){
        return $this->productRepository->statusChange($data, $id);
    }
    public function getVariantByProduct($data)
    {
        return $this->productRepository->getVariantByProduct($data);
    }

    public function getThisSKUProduct($id){
        return $this->productRepository->getThisSKUProduct($id);
    }

    public function variantDelete($id){
        return $this->productRepository->variantDelete($id);
    }

    public function getSellerBusinessInfo(){
        return $this->productRepository->getSellerBusinessInfo();
    }

    public function getSellerBankInfo(){
        return $this->productRepository->getSellerBankInfo();
    }

    public function get_seller_product_sku_wise_price($data){
        
        return $this->productRepository->get_seller_product_sku_wise_price($data);
    }

}
