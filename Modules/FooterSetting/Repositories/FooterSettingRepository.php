<?php
namespace Modules\FooterSetting\Repositories;

use App\Traits\ImageStore;
use Modules\FooterSetting\Entities\FooterContent;
use \Modules\FrontendCMS\Entities\FooterSetting;
use Modules\GeneralSetting\Entities\GeneralSetting;

class FooterSettingRepository {

    use ImageStore;
    protected $footer;

    public function __construct(GeneralSetting $footer)
    {
        $this->footer = $footer;
    }

    public function getAll(){
        return $this->footer::firstOrFail();
    }
    
    public function getFooterContent(){
        return FooterContent::first();
    }


    public function update($data, $id)
    {
        $item = $this->footer::where('id',$id)->first();
        
        return $this->footer::where('id',$id)->update([
            'footer_copy_right' => isset($data['copy_right'])?$data['copy_right']:$item->footer_copy_right,
            'footer_about_title' => isset($data['about_title'])?$data['about_title']:$item->footer_about_title,
            'footer_about_description' => isset($data['about_description'])?$data['about_description']:$item->footer_about_description,
            'footer_section_one_title' => isset($data['company_title'])?$data['company_title']:$item->footer_section_one_title,
            'footer_section_two_title' => isset($data['account_title'])?$data['account_title']:$item->footer_section_two_title,
            'footer_section_three_title' => isset($data['service_title'])?$data['service_title']:$item->footer_section_three_title
        ]);
        
    }

    public function updateAppLink($data){
        $content = FooterContent::first();

        if($content){
            $image_link = $content->payment_image;
            if(isset($data['payment_image'])){
                $this->deleteImage($image_link);
                $image_link = $this->saveImage($data['payment_image']);
            }
            $content->update([
                'payment_image' => $image_link,
                'app_store' => $data['app_store'],
                'play_store' => $data['play_store'],
                'show_play_store' => isset($data['play_store_show'])?1:0,
                'show_app_store' => isset($data['app_store_show'])?1:0,
                'show_payment_image' => isset($data['show_payment_image'])?1:0
            ]);
            return true;
        }
        return false;
    }

    public function edit($id){
        $footer = $this->footer->findOrFail($id);
        return $footer;
    }
}
