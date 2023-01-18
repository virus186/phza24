<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Seller\Entities\SellerProduct;
use CodeZero\UniqueTranslation\UniqueTranslationRule;

class CreateProductRequest extends FormRequest
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
                'product_name.'. $code =>'required|max:255',
                'product_type' => 'required',
                'category_ids' => 'required',
                'minimum_order_qty' => 'required',
                'tags' => 'required',
                'discount' => 'required',
                'weight' => 'nullable',
                'length' => 'nullable',
                'breadth' => 'nullable',
                'height' => 'nullable',
                'subtitle_1' => 'nullable|max:190',
                'subtitle_2' => 'nullable|max:190',
                'sku.*' => ['required_if:product_type,==,2',Rule::unique('product_sku', 'sku')->where(function($q){
                    return $q->where('product_id','!=',$this->id);
                })],
                'product_sku.'. $code => ['nullable', UniqueTranslationRule::for('product_sku', 'sku')->ignore($this->id)],
            ];
        }else{
            return [
                'product_name' =>'required|max:255',
                'product_type' => 'required',
                'category_ids' => 'required',
                'minimum_order_qty' => 'required',
                'tags' => 'required',
                'discount' => 'required',
                'weight' => 'nullable',
                'length' => 'nullable',
                'breadth' => 'nullable',
                'height' => 'nullable',
                'subtitle_1' => 'nullable|max:190',
                'subtitle_2' => 'nullable|max:190',
                'sku.*' => ['required_if:product_type,==,2',Rule::unique('product_sku', 'sku')->where(function($q){
                    return $q->where('product_id','!=',$this->id);
                })],
                'product_sku' => ['nullable',Rule::unique('product_sku', 'sku')->where(function($q){
                    return $q->where('product_id','!=',$this->id);
                })]
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
