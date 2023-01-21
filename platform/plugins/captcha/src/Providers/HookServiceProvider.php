<?php

namespace Botble\Captcha\Providers;

use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addSettings'], 299);
    }

    /**
     * @param string|null $data
     * @return string
     */
    public function addSettings(?string $data = null): string
    {
        return $data . view('plugins/captcha::setting')->render();
    }
}
