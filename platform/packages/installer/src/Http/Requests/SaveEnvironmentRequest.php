<?php

namespace Botble\Installer\Http\Requests;

use Botble\Support\Http\Requests\Request;

class SaveEnvironmentRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     *
     */
    public function rules(): array
    {
        return [
            'app_name' => 'required|string|max:50',
            'app_url' => 'required|url',
            'database_connection' => 'required|string|max:50',
            'database_hostname' => 'required|string|max:50',
            'database_port' => 'required|numeric',
            'database_name' => 'required|string|max:50',
            'database_username' => 'required|string|max:50',
            'database_password' => 'nullable|string|max:50',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'environment_custom.required_if' => trans('packages/installer::installer.environment.wizard.form.name_required'),
        ];
    }
}
