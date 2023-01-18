<?php

namespace Modules\Product\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoeryRequest extends FormRequest
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
                'name.'. $code => "required|unique_translation:categories,name,{$this->id}",
                'slug' => 'required|unique:categories,slug,'.$this->id,
                'status' => 'required',
                'searchable' => 'required',
                'commission_rate' => 'nullable|numeric',
                'google_product_category_id' => 'nullable|numeric',
                'image' => 'nullable|mimes:jpg,jpeg,png,bmp'
            ];
        }else{
            return [
                'name' => 'required|unique:categories,name,'.$this->id,
                'slug' => 'required|unique:categories,slug,'.$this->id,
                'status' => 'required',
                'searchable' => 'required',
                'commission_rate' => 'nullable|numeric',
                'google_product_category_id' => 'nullable|numeric',
                'image' => 'nullable|mimes:jpg,jpeg,png,bmp'
            ];
        }
    }

    public function messages()
    {
        if (isModuleActive('FrontendMultiLang')) {
            return [
                'name.*.required' => 'The category name is required',
                'name.*.unique_translation' => 'The category name has already been taken',
            ];
        }else{
            return [
                'name.required' => 'The category name is required',
                'name.unique' => 'The category name has already been taken',
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
