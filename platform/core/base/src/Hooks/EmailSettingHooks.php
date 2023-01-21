<?php

namespace Botble\Base\Hooks;

use EmailHandler;

class EmailSettingHooks
{
    /**
     * @param string|null $html
     * @return string
     */
    public static function addEmailTemplateSettings(?string $html): string
    {
        $templates = '';

        foreach (EmailHandler::getTemplates() as $type => $item) {
            foreach ($item as $module => $data) {
                $templates .= view('core/setting::template-line', compact('type', 'module', 'data'))->render();
            }
        }

        return $html . $templates;
    }
}
