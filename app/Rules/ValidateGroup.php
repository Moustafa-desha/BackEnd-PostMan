<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateGroup implements Rule
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
     * @param  mixed  $values
     * @return bool
     */
    public function passes($attribute, $values)
    {
        /**
         * pluck use to choose column in table direct
         */
        $GroupId = Group::pluck('id')->ToArray();
        foreach ($values as $value)
        {
            if (! in_array($value[0],$GroupId))
            {
                return false;
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The Group Already Exists';
    }
}
