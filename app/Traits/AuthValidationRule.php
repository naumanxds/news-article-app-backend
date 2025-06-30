<?php

namespace App\Traits;

trait AuthValidationRule
{
    public function loginValidationRules(): array
    {
        return [
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:6'
        ];
    }

    public function forgotPasswordValidationRules(): array
    {
        return [
            'email' => 'required|email|exists:users,email'
        ];
    }

    public function resetPasswordValidationRules(): array
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ];
    }
}
