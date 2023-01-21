<?php

use Botble\Ecommerce\Models\Product;

if (!function_exists('render_product_options')) {
    /**
     * @param Product $product
     * @param array $options
     * @return string
     */
    function render_product_options(Product $product, array $options = []): string
    {
        $script = 'vendor/core/plugins/ecommerce/js/change-product-options.js';

        Theme::asset()->container('footer')->add('change-product-options', $script, ['jquery']);

        $html = '';
        foreach ($options as $option) {
            $typeClass = __NAMESPACE__ . '\\' . $option['option_type'];
            if (class_exists($typeClass)) {
                $instance = new $typeClass();
                $html .= $instance->setOption($option)->setProduct($product)->render();
            } else {
                Log::error(sprintf('Class %s not found', $typeClass));
            }
        }

        if (!request()->ajax()) {
            return $html;
        }

        return $html . Html::script($script)->toHtml();
    }
}

if (!function_exists('render_product_options_info')) {
    /**
     * @param array $productOption
     * @param Product $product
     * @param bool $displayBasePrice
     * @return string
     */
    function render_product_options_info(array $productOption, Product $product, bool $displayBasePrice = false): string
    {
        $view = 'plugins/ecommerce::themes.options.render-options-info';

        $themeView = Theme::getThemeNamespace() . '::views.ecommerce.options.render-options-info';

        if (view()->exists($themeView)) {
            $view = $themeView;
        }

        return view($view, [
            'productOptions' => $productOption,
            'product' => $product,
            'displayBasePrice' => $displayBasePrice,
        ])->render();
    }
}
