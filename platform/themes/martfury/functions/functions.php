<?php

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Marketplace\Models\Store;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Kris\LaravelFormBuilder\FormHelper;
use Theme\Martfury\Fields\ThemeIconField;

register_page_template([
    'blog-sidebar' => __('Blog Sidebar'),
    'full-width' => __('Full width'),
    'homepage' => __('Homepage'),
    'coming-soon' => __('Coming soon'),
]);

register_sidebar([
    'id' => 'footer_sidebar',
    'name' => __('Footer sidebar'),
    'description' => __('Widgets in footer of page'),
]);

register_sidebar([
    'id' => 'bottom_footer_sidebar',
    'name' => __('Bottom Footer sidebar'),
    'description' => __('Widgets in bottom footer'),
]);

RvMedia::setUploadPathAndURLToPublic();

RvMedia::addSize('medium', 790, 510)->addSize('small', 300, 300);

if (is_plugin_active('ecommerce')) {
    add_action(BASE_ACTION_META_BOXES, function ($context, $object) {
        if (get_class($object) == ProductCategory::class && $context == 'advanced') {
            MetaBox::addMetaBox('additional_product_category_fields', __('Addition Information'), function () {
                $icon = null;
                $iconImage = null;
                $args = func_get_args();
                if (!empty($args[0])) {
                    $icon = MetaBox::getMetaData($args[0], 'icon', true);
                    $iconImage = MetaBox::getMetaData($args[0], 'icon_image', true);
                }

                return Theme::partial('product-category-fields', compact('icon', 'iconImage'));
            }, get_class($object), $context);
        }
    }, 24, 2);

    add_action([BASE_ACTION_AFTER_CREATE_CONTENT, BASE_ACTION_AFTER_UPDATE_CONTENT], function ($type, $request, $object) {
        if (get_class($object) == ProductCategory::class) {
            if ($request->has('icon')) {
                MetaBox::saveMetaBoxData($object, 'icon', $request->input('icon'));
            }

            if ($request->has('icon_image')) {
                MetaBox::saveMetaBoxData($object, 'icon_image', $request->input('icon_image'));
            }
        }
    }, 230, 3);

    app()->booted(function () {
        ProductCategory::resolveRelationUsing('icon', function ($model) {
            return $model->morphOne(MetaBoxModel::class, 'reference')->where('meta_key', 'icon');
        });
    });
}

add_action('init', function () {
    EmailHandler::addTemplateSettings(Theme::getThemeName(), [
        'name' => __('Theme emails'),
        'description' => __('Config email templates for theme'),
        'templates' => [
            'download_app' => [
                'title' => __('Download apps'),
                'description' => __('Send mail with links to download apps'),
                'subject' => __('Download apps'),
                'can_off' => true,
            ],
        ],
        'variables' => [],
    ], 'themes');
}, 125);

if (is_plugin_active('ads')) {
    AdsManager::registerLocation('top-slider-image-1', __('Top Slider Image 1 (deprecated)'))
        ->registerLocation('top-slider-image-2', __('Top Slider Image 2 (deprecated)'))
        ->registerLocation('product-sidebar', __('Product sidebar'));
}

add_action([BASE_ACTION_AFTER_CREATE_CONTENT, BASE_ACTION_AFTER_UPDATE_CONTENT], function ($type, $request, $object) {
    switch (get_class($object)) {
        case Store::class:
            if (Route::currentRouteName() == 'marketplace.vendor.settings.post') {
                if ($request->hasFile('cover_image_input')) {
                    $result = RvMedia::handleUpload($request->file('cover_image_input'), 0, 'stores');
                    if (!$result['error']) {
                        MetaBox::saveMetaBoxData($object, 'cover_image', $result['data']->url);
                    }
                } elseif ($request->has('cover_image') && !$request->input('cover_image')) {
                    MetaBox::deleteMetaData($object, 'cover_image');
                }
            }

            break;

        case SimpleSliderItem::class:
            if ($request->has('tablet_image')) {
                MetaBox::saveMetaBoxData($object, 'tablet_image', $request->input('tablet_image'));
            }

            if ($request->has('mobile_image')) {
                MetaBox::saveMetaBoxData($object, 'mobile_image', $request->input('mobile_image'));
            }

            break;
    }
}, 145, 3);

Form::component('themeIcon', Theme::getThemeNamespace() . '::partials.forms.icons-field', [
    'name',
    'value' => null,
    'attributes' => [],
]);

Form::component('themeBrand', Theme::getThemeNamespace() . '::partials.brands-field', [
    'name',
    'value' => null,
    'attributes' => [],
]);

add_filter('form_custom_fields', function (FormAbstract $form, FormHelper $formHelper) {
    if (!$formHelper->hasCustomField('themeIcon')) {
        $form->addCustomField('themeIcon', ThemeIconField::class);
    }

    return $form;
}, 29, 2);

add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function ($form, $data) {
    switch (get_class($data)) {
        case SimpleSliderItem::class:
            $form
                ->addAfter('image', 'tablet_image', 'mediaImage', [
                    'label' => __('Tablet Image'),
                    'label_attr' => ['class' => 'control-label'],
                    'value' => $data->getMetaData('tablet_image', true),
                    'help_block' => [
                        'text' => __('For devices with width from 768px to 1200px, if empty, will use the image from the desktop.'),
                    ],
                ])
                ->addAfter('tablet_image', 'mobile_image', 'mediaImage', [
                    'label' => __('Mobile Image'),
                    'label_attr' => ['class' => 'control-label'],
                    'value' => $data->getMetaData('mobile_image', true),
                    'help_block' => [
                        'text' => __('For devices with width less than 768px, if empty, will use the image from the tablet.'),
                    ],
                ]);

            break;
    }

    return $form;
}, 127, 3);
