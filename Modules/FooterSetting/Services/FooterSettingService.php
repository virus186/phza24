<?php

namespace Modules\FooterSetting\Services;

use Illuminate\Support\Facades\Validator;
use \Modules\FooterSetting\Repositories\FooterSettingRepository;

class FooterSettingService{

    
    protected $footerRepository;

    public function __construct(FooterSettingRepository $footerRepository)
    {
        $this->footerRepository = $footerRepository;
    }

    public function getAll()
    {
        return $this->footerRepository->getAll();
    }
    public function getFooterContent()
    {
        return $this->footerRepository->getFooterContent();
    }


    public function update($data,$id)
    {
        return $this->footerRepository->update($data, $id);
    }
    public function updateAppLink($data)
    {
        return $this->footerRepository->updateAppLink($data);
    }

    public function editById($id)
    {
        return $this->footerRepository->edit($id);
    }

}
