<?php

if (!function_exists('table_checkbox')) {
    /**
     * @param int $id
     * @return string
     * @throws Throwable
     */
    function table_checkbox($id): string
    {
        return view('core/table::partials.checkbox', compact('id'))->render();
    }
}

if (!function_exists('table_actions')) {
    /**
     * @param string|null $edit
     * @param string|null $delete
     * @param \Botble\Base\Models\BaseModel $item
     * @param string|null $extra
     * @return string
     */
    function table_actions(?string $edit, ?string $delete, $item, ?string $extra = null): string
    {
        return view('core/table::partials.actions', compact('edit', 'delete', 'item', 'extra'))->render();
    }
}
