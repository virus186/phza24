<?php
namespace Modules\Product\Repositories;

use App\Traits\ImageStore;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\CategoryImage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Product\Imports\CategoryImport;
use Modules\Product\Export\CategoryExport;

class CategoryRepository
{
    use ImageStore;

    protected $category;
    protected $ids = [];

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function category()
    {
        return Category::with(['brands', 'categoryImage', 'groups.categories','subCategories'])->where("parent_id", 0)->paginate(10);
    }
    public function activeCategory()
    {
        return Category::with(['brands', 'categoryImage', 'groups.categories','subCategories'])->where("parent_id", 0)->where('status', 1)->paginate(20);
    }

    public function getData(){
        return Category::with(['subCategories','categoryImage'])->latest();
    }

    public function subcategory($category)
    {
        return Category::where("parent_id", $category)->where('status', 1)->get();
    }

    public function allSubCategory()
    {
        return Category::where("parent_id", "!=", 0)->get();
    }

    public function getAllSubSubCategoryID($category_id){

        $subcats = $this->subcategory($category_id);
        $this->unlimitedSubCategory($subcats);
        return $this->ids;
    }

    private function unlimitedSubCategory($subcats){

        foreach($subcats as $subcat){
            $this->ids[] = $subcat->id;
            $this_subcats = $this->subcategory($subcat->id);
            if($this_subcats->count() > 0){
                $this->unlimitedSubCategory($this_subcats);
            }
        }
    }

    public function getModel(){

        return $this->category;
    }

    public function getAll()
    {
        if(isModuleActive('Affiliate')){
            return Category::with(['parentCategory','categoryImage','brands','affiliateCategoryCommission'])->take(100)->get();
        }else{
            return Category::with(['parentCategory','categoryImage','brands'])->take(100)->get();
        }

    }

    public function getActiveAll(){
        return Category::with('categoryImage', 'parentCategory', 'subCategories')->where('status', 1)->latest()->get();
    }

    public function getCategoryByTop(){

        return Category::with('categoryImage', 'parentCategory', 'subCategories')->where('status', 1)->orderBy('total_sale', 'desc')->get();
    }

    public function save($data)
    {
        if(isset($data['category_type'])){
            $parent_depth = Category::where('id', $data['parent_id'])->first();
            $data['depth_level'] = $parent_depth->depth_level + 1;
        }else{
            $data['depth_level'] = 1;
        }
        $data['commission_rate'] = isset($data['commission_rate'])?$data['commission_rate']:0;
        $data['parent_id'] = isset($data['category_type'])?$data['parent_id']:0;
        $data['google_product_category_id'] = isset($data['google_product_category_id'])?$data['google_product_category_id']:0;

        $category = new Category();
        $category->fill($data)->save();
        if(!empty($data['image'])){
            $data['image'] = ImageStore::saveImage($data['image'], 225, 225);
            CategoryImage::create([
                'category_id' => $category->id,
                'image' => $data['image']
            ]);
        }
        return true;
    }

    public function update($data, $id)
    {
        $category = Category::where('id',$id)->first();
        if(isset($data['category_type'])){
            $parent_depth = Category::where('id', $data['parent_id'])->first();
            $data['depth_level'] = $parent_depth->depth_level + 1;
        }else{
            $data['depth_level'] = 1;
        }
        $data['commission_rate'] = isset($data['commission_rate'])?$data['commission_rate']:0;
        $data['parent_id'] = isset($data['category_type'])?$data['parent_id']:0;
        $data['google_product_category_id'] = isset($data['google_product_category_id'])?$data['google_product_category_id']:0;

        $category->fill($data)->save();

        if(!empty($data['image'])){

            $data['image'] = ImageStore::saveImage($data['image'], 225, 225);

            if(@$category->categoryImage->image){
                ImageStore::deleteImage(@$category->categoryImage->image);
                @$category->categoryImage->update([
                    'image' => $data['image']
                ]);

            }else{
                CategoryImage::create([
                    'category_id' => $id,
                    'image' => $data['image']
                ]);
            }
        }
        return true;
    }

    public function delete($id)
    {

        $category = $this->category->findOrFail($id);

        if (count($category->products) > 0 || count($category->subCategories) > 0
        || count($category->newUserZoneCategories) > 0 || count($category->newUserZoneCouponCategories) > 0 ||
         count($category->MenuElements) > 0 || count($category->MenuRightPanel) > 0 || count($category->Silders) > 0 || count($category->headerCategoryPanel) > 0 ||
          count($category->homepageCustomCategories) > 0) {
            return "not_possible";
        }

        if(@$category->categoryImage){
            ImageStore::deleteImage(@$category->categoryImage->image);
        }
        $category->delete();

        return 'possible';
    }

    public function checkParentId($id){
        $categories = Category::where('parent_id',$id)->get();
    }

    public function show($id)
    {
        $category = $this->category->with('categoryImage', 'parentCategory', 'subCategories.categoryImage', 'subCategories.subCategories.categoryImage')->where('id', $id)->first();
        return $category;
    }

    public function edit($id){
        $category = $this->category->findOrFail($id);
        return $category;
    }

    public function findBySlug($slug)
    {
        return $this->category->where('slug', $slug)->first();
    }

    public function csvUploadCategory($data)
    {
        Excel::import(new CategoryImport, $data['file']->store('temp'));
    }

    public function csvDownloadCategory()
    {
        if (file_exists(storage_path("app/category_list.xlsx"))) {
          unlink(storage_path("app/category_list.xlsx"));
        }
        return Excel::store(new CategoryExport, 'category_list.xlsx');
    }

    public function getCategoryBySearch($search){
        $items = collect();
        if($search != ''){
            $items = Category::with('subCategories')->where('status', 1)->where('name', 'LIKE', "%{$search}%")->paginate(10);
        }else{
            $items = Category::with('subCategories')->where('parent_id', 0)->where('status', 1)->paginate(10);
        }
        $response = [];
        foreach($items as $category){
            $level = '';
            for($i = 1; $i <= $category->depth_level ; $i++){
                $level .= '-';
            }
            $level .= '> ';
            $response[]  =[
                'id'    =>$category->id,
                'text'  => $level.$category->name
            ];

            if($category->subCategories->count() > 1){
                $subData = $this->recuseSub($category->subCategories, $response);
                $response = $subData;
            }
            
        }

        return  $response;
    }
    private function recuseSub($subcategories, $response){
        foreach($subcategories as $subcat){
            $level = '';
            for($i = 1; $i <= $subcat->depth_level ; $i++){
                $level .= '-';
            }
            $level .= '> ';
            $response[]  =[
                'id'    =>$subcat->id,
                'text'  =>$level.$subcat->name
            ];
            if($subcat->subCategories->count() > 1){
                $subData = $this->recuseSub($subcat->subCategories, $response);
                $response = $subData;
            }
        }
        return $response;
    }

    public function firstCategory(){
        $category = Category::where('parent_id', 0)->where('status', 1)->first();
        if($category){
            return $category;
        }
        return null;
    }

    public function getParentCategoryBySearch($search){
        $items = collect();
        if($search != ''){
            $items = Category::where('status', 1)->where('parent_id', 0)->where('name', 'LIKE', "%{$search}%")->paginate(10);
        }else{
            $items = Category::where('parent_id', 0)->where('status', 1)->paginate(10);
        }
        $response = [];
        foreach($items as $category){
            $level = '';
            for($i = 1; $i <= $category->depth_level ; $i++){
                $level .= '-';
            }
            $level .= '> ';
            $response[]  =[
                'id'    =>$category->id,
                'text'  => $level.$category->name
            ];
        }
        return $response;
    }
}
