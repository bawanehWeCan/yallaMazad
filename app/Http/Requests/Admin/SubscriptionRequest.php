<?php

namespace App\Http\Requests\Admin;

use App\Rules\DeterminEndDate;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
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
            'user_id'=>'required|exists:users,id',
            'start_date'=>'required|date',
            'end_date'=>['required','date',new DeterminEndDate($this->start_date)],
            'plan_id'=>'required|exists:plans,id',

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
            'plan_id' => 'plan',
        ];
    }
}
