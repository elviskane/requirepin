<?php

namespace Ikechukwukalu\Requirepin\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class CurrentPin implements Rule
{

    private bool $defaultPin = false;
    private bool $allowDefaultPin = false;
    private $user_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(bool $allowDefaultPin = false,$id)
    {
        //
        $this->allowDefaultPin = $allowDefaultPin;
        $this->user_id = $id;
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
        $user = User::find($this->user_id);
        if ($user->default_pin && !$this->allowDefaultPin) {
            $this->defaultPin = true;

            return false;
        }

        return Hash::check($value, $user->pin);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->defaultPin) {
            return trans('requirepin::pin.default');
        }

        return trans('requirepin::pin.wrong');
    }
}
