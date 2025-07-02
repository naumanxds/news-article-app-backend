<?php

namespace App\Traits;

trait TagValidationRule
{
    public function storeTagValidationRules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:tags,name',
        ];
    }
}
