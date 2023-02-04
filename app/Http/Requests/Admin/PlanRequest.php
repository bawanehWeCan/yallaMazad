<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
       /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'en' => 'required|min:4|max:255',
            'ar' => 'required|min:4|max:255',
            'details_en'=>'required|min:4|max:2000',
            'details_ar'=>'required|min:4|max:2000',
            'price'=>'required|numeric',
            'number_of_auction'=>'required|numeric',
            'time'=>'required|numeric',
            'point_one'=>'nullable|min:4|max:2000',
            'point_two'=>'nullable|min:4|max:2000',
            'point_three'=>'nullable|min:4|max:2000',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'en' => 'english name',
            'ar' => 'arabic name',
            'details_en' => 'english details',
            'details_ar' => 'arabic details',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
