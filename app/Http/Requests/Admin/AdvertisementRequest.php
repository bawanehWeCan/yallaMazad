<?php

namespace App\Http\Requests\Admin;

use App\Rules\DeterminEndDate;
use Illuminate\Foundation\Http\FormRequest;

class AdvertisementRequest extends FormRequest
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
            'name'=>'required|min:4|max:255',
            'content'=>'required|min:4|max:2000',
            'start_price'=>'required|numeric',
            'status'=>'required|in:pending,approve,rejected,current,complete',
            'reject_description'=>'required_if:status,rejected|nullable|min:4|max:10000',
            'buy_now_price'=>'required|numeric',
            'category_id'=>'required|exists:categories,id',
            'price_one'=>'nullable|numeric',
            'price_two'=>'nullable|numeric',
            'price_three'=>'nullable|numeric',
            'start_date'=>'required',
            'end_date'=>['required',new DeterminEndDate($this->start_date)],

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
            'user_id' => 'user',
            'category_id' => 'category',
        ];
    }
}
