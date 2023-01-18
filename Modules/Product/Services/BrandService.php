<?php

namespace Modules\Product\Services;
use \Modules\Product\Repositories\BrandRepository;
use App\Traits\ImageStore;
use Modules\Product\Entities\Brand;

class BrandService
{
    use ImageStore;
    protected $brandRepository;

    public function __construct(BrandRepository  $brandRepository)
    {
        $this->brandRepository= $brandRepository;
    }

    public function save($data)
    {
        if (!empty($data['featured'])) {
            $data['featured'] = 1;
        }else {
            $data['featured'] = 0;
        }
        if(isset($data['logo'])){
            $imageName = ImageStore::saveImage($data['logo'],150,150);
            $data['logo'] = $imageName;
        }
        return $this->brandRepository->create($data);
    }

    public function update($data,$id)
    {
        if (!empty($data['featured'])) {
            $data['featured'] = 1;
        }else {
            $data['featured'] = 0;
        }
        if (!empty($data['logo'])) {
            $brand = Brand::findOrFail($id);
            ImageStore::deleteImage($brand->logo);
            $imageName = ImageStore::saveImage($data['logo'],150,150);
            $data['logo'] = $imageName;
        }
        return $this->brandRepository->update($data, $id);
    }

    public function getAll()
    {
        return $this->brandRepository->getAll();
    }
    public function getAllCount(){
        return $this->brandRepository->getAllCount();
    }
    public function getActiveAll()
    {
        return $this->brandRepository->getActiveAll();
    }


    public function getBySearch($data)
    {
        return $this->brandRepository->getBySearch($data);
    }

    public function getByPaginate($count)
    {
        return $this->brandRepository->getByPaginate($count);
    }

    public function getBySkipTake($skip, $take)
    {
        return $this->brandRepository->getBySkipTake($skip, $take);
    }

    public function getbrandbySort()
    {
        return $this->brandRepository->getbrandbySort();
    }

    public function deleteById($id)
    {
        $brand = Brand::findOrFail($id);
        if(count($brand->products) < 1){
            ImageStore::deleteImage($brand->logo);
        }
        return $this->brandRepository->delete($id);
    }

    public function findById($id)
    {
        return $this->brandRepository->find($id);
    }

    public function findBySlug($slug)
    {
        return $this->brandRepository->findBySlug($slug);
    }

    public function csvUploadBrand($data)
    {
        return $this->brandRepository->csvUploadBrand($data);
    }

    public function csvDownloadBrand()
    {
        return $this->brandRepository->csvDownloadBrand();
    }

    public function getBrandsByAjax($search){
        return $this->brandRepository->getBrandsByAjax($search);
    }
}
