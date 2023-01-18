<?php
namespace Modules\FrontendCMS\Repositories;

use App\Traits\ImageStore;
use Modules\FrontendCMS\Entities\HomepageCustomBrand;
use Modules\FrontendCMS\Entities\HomepageCustomCategory;
use Modules\FrontendCMS\Entities\HomepageCustomProduct;
use Modules\FrontendCMS\Entities\HomePageCustomSection;
use Modules\FrontendCMS\Entities\HomePageSection;
use Modules\Product\Entities\Brand;
use Modules\Product\Entities\Category;
use Modules\Seller\Entities\SellerProduct;

class WidgetRepository {
    use ImageStore;

    public function getAll(){

        if(app('theme')->folder_path == 'amazy'){
            $sections =  HomePageSection::where('theme', 'LIKE', '%amazy%')->get();
        }else{
            $sections =  HomePageSection::where('theme', 'LIKE', '%default%')->get();
        }
        return $sections;
        
    }

    public function getBySectionName($data){
        return HomePageSection::where('section_name',$data['value'])->first();
    }
    public function getProducts(){
        return SellerProduct::with('product')->where('status',1)->activeSeller()->get();
    }
    public function getCategories(){
        return Category::where('status',1)->get();
    }
    public function getBrands(){
        return Brand::where('status',1)->get();
    }

    public function update($data){

        HomePageSection::findOrFail($data['id'])->update([
            'title' => $data['title'],
            'column_size' => $data['column_size'],
            'status' => $data['status'],
            'type' => isset($data['type'])?$data['type']:1
        ]);
        if($data['form_for'] == 'best_deals' || $data['form_for'] == 'top_picks' || $data['form_for'] == 'more_products' || $data['form_for'] == 'top_rating' || $data['form_for'] == 'people_choices' || $data['form_for'] == 'max_sale'){

            $section = HomePageSection::where('id',$data['id'])->first();
            foreach($section->products as $product){
                foreach($data['product_list'] as $key => $item){
                    if($product->seller_product_id != $item){
                        $product->delete();
                    }
                }
            }

            foreach($data['product_list'] as $key => $item){

                HomepageCustomProduct::where('section_id',$data['id'])->updateOrCreate([
                    'section_id' => $data['id'],
                    'seller_product_id' => $data['product_list'][$key]
                ]);
            }
        }
        elseif($data['form_for'] == 'top_brands'){
            $section = HomePageSection::where('id',$data['id'])->first();
            foreach($section->brands as $brand){
                foreach($data['brand_list'] as $key => $item){
                    if($brand->brand_id != $item){
                        $brand->delete();
                    }
                }
            }

            foreach($data['brand_list'] as $key => $item){

                HomepageCustomBrand::where('section_id',$data['id'])->updateOrCreate([
                    'section_id' => $data['id'],
                    'brand_id' => $data['brand_list'][$key]
                ]);
            }
        }

        elseif($data['form_for'] == 'feature_categories'){
            $section = HomePageSection::where('id',$data['id'])->first();
            foreach($section->categories as $category){
                foreach($data['category_list'] as $key => $item){
                    if($category->category_id != $item){
                        $category->delete();
                    }
                }
            }


            foreach($data['category_list'] as $key => $item){

                HomepageCustomCategory::where('section_id',$data['id'])->updateOrCreate([
                    'section_id' => $data['id'],
                    'category_id' => $data['category_list'][$key]
                ]);
            }
        }

        elseif($data['form_for'] == 'filter_category'){
            $section = HomePageCustomSection::where('section_id', $data['id'])->first();
            if(isset($data['banner_image'])){
                ImageStore::deleteImage($section->field_2);
                $image = ImageStore::saveImage($data['banner_image']);
            }else{
                $image = $section->field_2;
            }
            $section->update([
                'field_1' => $data['category'],
                'field_2' => $image
            ]);
        }
        elseif($data['form_for'] == 'discount_banner'){
            $section = HomePageCustomSection::where('section_id', $data['id'])->first();
            if(isset($data['banner_image_1'])){
                ImageStore::deleteImage($section->field_1);
                $image_1 = ImageStore::saveImage($data['banner_image_1']);
            }else{
                $image_1 = $section->field_1;
            }
            if(isset($data['banner_image_2'])){
                ImageStore::deleteImage($section->field_2);
                $image_2 = ImageStore::saveImage($data['banner_image_2']);
            }else{
                $image_2 = $section->field_2;
            }
            if(isset($data['banner_image_3'])){
                ImageStore::deleteImage($section->field_3);
                $image_3 = ImageStore::saveImage($data['banner_image_3']);
            }else{
                $image_3 = $section->field_3;
            }

            $section->update([
                'field_1' => $image_1,
                'field_2' => $image_2,
                'field_3' => $image_3,
                'field_4' => $data['section_1_link'],
                'field_5' => $data['section_2_link'],
                'field_6' => $data['section_3_link'],
            ]);
        }


        return true;

    }

}
