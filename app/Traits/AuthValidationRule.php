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
}
