<?php

namespace Asaa\Validation\Rules;

class Required implements ValidationRule
{
    public function message(): string
    {
        return "this field is required";
    }

    public function isValid(string $field, array $data): bool
    {
        return isset($data[$field]) && $data[$field]!== '';
    }
}
