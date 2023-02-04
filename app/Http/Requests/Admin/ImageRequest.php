<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
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
            'images'=>'required|array|min:1',
            'images.*'=>'required|mimes:jpg,jpeg,gif,png',
            'advertisement_id'=>'required|exists:advertisements,id',
        ];
    }

    public function attributes()
    {
        return [
            'advertisement_id' => 'advertisement',
            'images.*'=>'images',
        ];
    }
}
