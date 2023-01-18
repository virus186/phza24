<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (isModuleActive('FrontendMultiLang')) {
            $code = auth()->user()->lang_code;
            return [
                'name.'. $code => "required|max:255|unique_translation:unit_types,name,{$this->id}",
                "status" => "required",
            ];
        }else{
            return [
                'name' => 'required|max:255|unique:unit_types,name,'.$this->id,
                'status' => 'required'
            ];
        }
    }
    public function messages()
    {
        if (isModuleActive('FrontendMultiLang')) {
            return [
                'name.*.required' => 'The Unit name is required',
                'name.*.unique_translation' => 'The Unit name has already been taken',
            ];
        }else{
            return [
                'name.required' => 'The Unit name is required',
                'name.unique' => 'The Unit name has already been taken',
            ];
        }
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
