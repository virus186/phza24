<?php

namespace Modules\Appearance\Repositories;

use File;
class CustomAssetRepository
{
    public function getFileContent(){
        $data = [];
        if (file_exists(base_path('public/css/custom.css'))) {
            $data['custom_css'] = file_get_contents(base_path('public/css/custom.css'));
        }
        if (file_exists(base_path('public/js/custom.js'))) {
            $data['custom_js'] = file_get_contents(base_path('public/js/custom.js'));
        }
        return $data;
    }

    public function updateCustomFile($data){
        if($data['custom_css'] == null){
            $data['custom_css'] = '';
        }
        File::put(base_path('public/css/custom.css'), $data['custom_css']);

        if($data['custom_js'] == null){
            $data['custom_js'] = '';
        }
        File::put(base_path('public/js/custom.js'), $data['custom_js']);
        
        return true;
    }
}
