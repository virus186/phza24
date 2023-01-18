<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributeFormRequest extends FormRequest
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
                'name.'. $code => "required|max:255|unique_translation:attributes,name,{$this->id}",
                "status" => "required",
            ];
        }else{
            return [
                'name' => 'required|unique:attributes,name,'.$this->id,
                "status" => "required",
            ];
        }
    }
    public function messages()
    {
        if (isModuleActive('FrontendMultiLang')) {
            return [
                'name.*.required' => 'The attribute name is required',
                'name.*.unique_translation' => 'The attribute name has already been taken',
            ];
        }else{
            return [
                'name.required' => 'The attribute name is required',
                'name.unique' => 'The attribute name has already been taken',
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
