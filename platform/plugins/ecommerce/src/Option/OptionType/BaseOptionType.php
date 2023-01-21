<?php

namespace Botble\Ecommerce\Option\OptionType;

use Botble\Ecommerce\Models\Product;
use Theme;

abstract class BaseOptionType
{
    /**
     * @var string
     */
    public $option = null;

    /**
     * @var Product
     */
    public $product = null;

    /**
     * @param $option
     * @return $this
     */
    public function setOption($option): self
    {
        $this->option = $option;

        return $this;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    abstract public function view(): string;

    /**
     * @return string
     */
    public function render(): string
    {
        $view = 'plugins/ecommerce::themes.options.' . $this->view();

        $themeView = Theme::getThemeNamespace() . '::views.ecommerce.options.' . $this->view();

        if (view()->exists($themeView)) {
            $view = $themeView;
        }

        return view($view, ['option' => $this->option, 'product' => $this->product])->render();
    }
}
