<?php

namespace Modules\FrontendCMS\Repositories;

use App\Traits\ImageStore;
use \Modules\FrontendCMS\Entities\SubscribeContent;

class PromotionbarRepository
{
    use ImageStore;

    public function getContent()
    {
        return SubscribeContent::where('id', 3)->first();
    }

    public function update($data)
    {
        $promotion = SubscribeContent::findOrFail($data->id);
        if ($data->hasFile('file')) {
            $file = $data->file('file');
            $this->deleteImage($promotion->image);
            $filename = $this->saveImage($file);
        }else{
            $filename = $promotion->image;
        }
        return $promotion->update([
            'status' => (isset($data['status']) && $data['status'] == 1)?1:0,
            'image' => $filename,
            'description' => $data['link']
        ]);
        return 1;
    }

    public function getAdsContent(){
        return SubscribeContent::where('id', 4)->first();
    }
}
