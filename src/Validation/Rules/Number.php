<?php

namespace Asaa\Validation\Rules;

class Number implements ValidationRule
{
    public function message(): string
    {
        return "This field most be numeric";
    }

    public function isValid(string $field, array $data): bool
    {
        return isset($data[$field]) && is_numeric($data[$field]);
    }
}
