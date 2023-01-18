<?php
namespace App\Services;

use App\Repositories\ProductRepository;


class ProductService{

    protected $productRepository;

    public function __construct(ProductRepository $productRepository){
        $this->productRepository = $productRepository;
    }

    public function getProductBySlug($slug)
    {
        return $this->productRepository->getProductBySlug($slug);
    }

    public function getActiveSellerProductBySlug($slug, $seller_slug = null)
    {
        return $this->productRepository->getActiveSellerProductBySlug($slug, $seller_slug);
    }

    public function getProductByID($id){
        return $this->productRepository->getProductByID($id);
    }

    public function recentViewIncrease($id){
        return $this->productRepository->recentViewIncrease($id);
    }

    public function recentViewStore($seller_product_id)
    {
        return $this->productRepository->recentViewStore($seller_product_id);
    }

    public function lastRecentViewinfo()
    {
        return $this->productRepository->lastRecentViewinfo();
    }

    public function getReviewByPage($data){
        return $this->productRepository->getReviewByPage($data);
    }

    public function recentViewedLast3Product($id){
        return $this->productRepository->recentViewedLast3Product($id);
    }

    public function getPickupByCity($data){
        return $this->productRepository->getPickupByCity($data);
    }

    public function getPickupById($data){
        return $this->productRepository->getPickupById($data);
    }

    public function getLowestShippingFromSeller($data){
        return $this->productRepository->getLowestShippingFromSeller($data);
    }

}
