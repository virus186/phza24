<?php

namespace Modules\Appearance\Repositories;

use App\Traits\ImageStore;
use Modules\GeneralSetting\Entities\GeneralSetting;

class PreloaderRepository
{
    use ImageStore;

    public function updatePreloader($data){
        if(!empty($data['preloader_image'])){
            $this->deleteImage(app('general_setting')->preloader_image);
            $image = $data->file('preloader_image'); 
            $exp_image = explode('.',$image->getClientOriginalName());
            $imageexptype = $exp_image[1];
            $img = 'uploads/settings'.'/'.uniqid().'.'.$imageexptype;
            $image->move(asset_path('uploads/settings'), asset_path($img));
        }else{
            $img = app('general_setting')->preloader_image;
        }

        GeneralSetting::first()->update([
            'preloader_status' => $data['preloader_status'],
            'preloader_type' => $data['preloader_type'],
            'preloader_image' => $img,
            'preloader_style' => $data['preloader_style']
        ]);

        return true;
    }
}
