<?php

namespace Botble\Ecommerce\Forms;

use Assets;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Enums\GlobalOptionEnum;
use Botble\Ecommerce\Http\Requests\GlobalOptionRequest;
use Botble\Ecommerce\Models\GlobalOption;

class GlobalOptionForm extends FormAbstract
{
    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        Assets::addScripts(['jquery-ui'])->addScriptsDirectly([
            'vendor/core/plugins/ecommerce/js/global-option.js',
        ]);

        $this
            ->setupModel(new GlobalOption())
            ->setValidatorClass(GlobalOptionRequest::class)
            ->withCustomFields()
            ->add('option_name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('option_type', 'customSelect', [
                'label' => trans('plugins/ecommerce::product-option.option_type'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => ['class' => 'form-control option-type'],
                'choices' => GlobalOptionEnum::options(),
            ])
            ->add('required', 'onOff', [
                'label' => trans('plugins/ecommerce::product-option.required'),
                'label_attr' => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->setBreakFieldPoint('option_type')
            ->addMetaBoxes([
                'option_value' => [
                    'title' => trans('plugins/ecommerce::product-option.option_value'),
                    'content' => view(
                        'plugins/ecommerce::product-options.option-admin',
                        ['option' => $this->getModel()->toArray()]
                    )->render(),
                ],
            ]);
    }
}
