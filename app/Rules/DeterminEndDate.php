<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DeterminEndDate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $start_date;
    public function __construct($start_date)
    {
        $this->start_date=$start_date;
    }

    /**
     * Determine if the validation rule passes.
     * check end date to determine in validation
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($value != null && $value <= $this->start_date){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('End date value must be after start date value');
    }
}
