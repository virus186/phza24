<?php
namespace App\Repositories;

use App\Models\MediaManager;
use App\Traits\ImageStore;
use Illuminate\Support\Facades\DB;

class MediaManagerRepository
{
    use ImageStore;
    public function getFiles($data){
        $query = MediaManager::query();
        $seller_id = getParentSellerId();
        $query = $query->where('user_id', $seller_id);
        if(isset($data['search']) && !empty($data['search'])){
            $slugs = explode(' ', $data['search']);
            if(isset($data['sort']) && $data['sort'] != 'newest'){
                if($data['sort'] == 'oldest'){
                    $query =  $query->orderBy('id','asc')->where(function($q) use($slugs){
                        foreach($slugs as $slug){
                            $q = $q->orWhere('orginal_name', 'LIKE', "%{$slug}%");
                        }
                        return $q;
                    });
                }elseif($data['sort'] == 'smallest'){
                    $query =  $query->orderBy('size')->where(function($q) use($slugs){
                        foreach($slugs as $slug){
                            $q = $q->orWhere('orginal_name', 'LIKE', "%{$slug}%");
                        }
                        return $q;
                    });
                }
                elseif($data['sort'] == 'bigest'){
                    $query =  $query->orderByDesc('size')->where(function($q) use($slugs){
                        foreach($slugs as $slug){
                            $q = $q->orWhere('orginal_name', 'LIKE', "%{$slug}%");
                        }
                        return $q;
                    });
                }
            }
            $query =  $query->orderByDesc('id')->where(function($q) use($slugs){
                foreach($slugs as $slug){
                    $q = $q->orWhere('orginal_name', 'LIKE', "%{$slug}%");
                }
                return $q;
            });
        }else{
            if(isset($data['sort']) && $data['sort'] != 'newest'){
                if($data['sort'] == 'oldest'){
                    $query =  $query->orderBy('id');
                }elseif($data['sort'] == 'smallest'){
                    $query =  $query->orderBy('size');
                }
                elseif($data['sort'] == 'bigest'){
                    $query =  $query->orderByDesc('size');
                }
            }

            $query =  $query->orderByDesc('id');
        }
        return $query->paginate(18);
    }

    public function store($request){
        $file_info = $this->mediaUpload($request->file);
        $file_info['user_id'] = getParentSellerId();
        $data = DB::table('media_managers')->insert($file_info);
        return $data;
    }

    public function destroy($id){
        $file = MediaManager::where('id', $id)->where('user_id', getParentSellerId())->first();
        if($file){
            foreach($file->used_media as $media){
                if($media->usable_type == 'Modules\Product\Entities\Product'){
                    if($media->used_for = 'meta_image'){
                        if($media->usable){
                            $this->deleteImage(@$media->usable->meta_image);
                            $media->usable->update([
                                'meta_image' => null
                            ]);
                        }
                    }
                }
                elseif($media->usable_type == 'Modules\Product\Entities\ProductSku'){
                    if($media->used_for = 'variant_image'){
                        if($media->usable){
                            $this->deleteImage(@$media->usable->variant_image);
                            $media->usable->update([
                                'variant_image' => null
                            ]);
                        }
                    }
                }
                elseif($media->usable_type == 'Modules\Seller\Entities\SellerProduct'){
                    if($media->used_for = 'thumb_image'){
                        if($media->usable){
                            $this->deleteImage(@$media->usable->thum_img);
                            $media->usable->update([
                                'thum_img' => null
                            ]);
                        }
                    }
                }
                $media->delete();
            }
            foreach($file->gallery_images as $image){
                $this->deleteImage($image->images_source);
                $ids = explode(',',$image->product->media_ids);
                foreach($ids as $key => $iid){
                    if($iid == $id){
                        unset($ids[$key]);
                        $this->deleteImage($image->product->thumbnail_image_source);
                        $image->product->update([
                            'media_ids' => implode(',',$ids),
                            'thumbnail_image_source' => null
                        ]);
                    }
                }

                $image->delete();
            }
            $this->deleteImage($file->file_name);
            $file->delete();
            return true;
        }
        return false;
    }

    public function getMediaById($request){
        $data = MediaManager::whereIn('id', $request->ids);
        if($request->prev_ids){
            $new_ids = implode(',',$request->ids);
            $prev_ids = $request->prev_ids.','.$new_ids;
            $data->orderByRaw('FIELD(id, '.$prev_ids.')');
        }
        return $data->get();
    }
}
