<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UserValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        return
        $nurse = User::where('user_type','nurse')->get();
        if($value==$nurse)
        {
            return 123;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Only Admins can access to this site!';
    }
}
