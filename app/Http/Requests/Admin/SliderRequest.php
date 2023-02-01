<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'en' => 'required|min:4|max:255',
            'ar' => 'required|min:4|max:255',
            'image'=>'required_without:id|mimes:jpg,gif,jpeg,png',
            'route_type'=>'required|min:4|max:255',
            'route_id'=>'required|numeric',
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
        ];
    }
}
