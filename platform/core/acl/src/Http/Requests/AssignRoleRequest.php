<?php

namespace Botble\ACL\Http\Requests;

use Botble\Support\Http\Requests\Request;

class AssignRoleRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pk' => 'required|integer|min:1',
            'value' => 'required|integer|min:1',
        ];
    }
}
