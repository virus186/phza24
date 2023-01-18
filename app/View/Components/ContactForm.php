<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Schema;
use Illuminate\View\Component;
use Nwidart\Modules\Facades\Module;
use Modules\FormBuilder\Repositories\FormBuilderRepositories;
use \Modules\FrontendCMS\Services\ContactContentService;
use \Modules\FrontendCMS\Services\QueryService;

class ContactForm extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    protected $contactContentService;
    protected $queryService;

    public function __construct(ContactContentService $contactContentService, QueryService $queryService)
    {
        $this->contactContentService = $contactContentService;
        $this->queryService = $queryService;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $contactContent = $this->contactContentService->getAll();
        $QueryList = $this->queryService->getAllActive();
        $row = '';
        $form_data = '';
        if(Module::has('FormBuilder')){
            if(Schema::hasTable('custom_forms')){
                $formBuilderRepo = new FormBuilderRepositories();
                $row = $formBuilderRepo->find(4);
                if($row->form_data){
                    $form_data = json_decode($row->form_data);
                }
            }
        }
        return view(theme('components.contact-form'),compact('contactContent','QueryList','row','form_data'));
    }
}
