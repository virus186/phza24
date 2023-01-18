<?php
namespace Modules\FrontendCMS\Repositories;

use App\Traits\ImageStore;
use \Modules\FrontendCMS\Entities\AboutUs;
use Modules\FrontendCMS\Entities\LoginPage;

class LoginPageRepository {
    use ImageStore;
    public function loginPageUpdate($data)
    {
        $loginPage = LoginPage::where('login_slug',$data['login_slug'])->first();

        if($loginPage){
            if (isset($data['cover_image'])) {
                $filename = $this->saveImage($data['cover_image']);
            }else{
                $filename = $loginPage->cover_img;
            }
            $loginPage->update([
                'title' => $data['title'],
                'sub_title' => $data['sub_title'],
                'cover_img' => $filename
            ]);
            return true;
        }
        return false;
    }

}
