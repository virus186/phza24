<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use CodeZero\UniqueTranslation\UniqueTranslationRule;

class UpdateBrandRequest extends FormRequest
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
                'name.'. $code => ['required', UniqueTranslationRule::for('brands', 'name')->ignore($this->id),
                ],
                "status" => "required",
                'logo' => 'mimes:jpeg,jpg,png,gif|nullable'
            ];
        }else{

            return [
                "name" => [
                    'required',
                    Rule::unique('brands', 'name')->ignore($this->id)
                ],
                "status" => "required",
                'logo' => 'mimes:jpeg,jpg,png,gif|nullable'
            ];
        }
    }

    public function messages()
    {
        if (isModuleActive('FrontendMultiLang')) {
            return [
                'name.*.required' => 'The Brand name is required',
                'name.*.unique_translation' => 'The Brand name has already been taken',
            ];
        }else{
            return [
                'name.required' => 'The Brand name is required',
                'name.unique' => 'The Brand name has already been taken',
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
