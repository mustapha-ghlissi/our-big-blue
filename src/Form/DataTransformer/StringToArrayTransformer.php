<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

final class StringToArrayTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): ?string
    {
        if (!is_array($value)) {
            return null;
        }

        return implode(',', $value);
    }

    public function reverseTransform(mixed $value): ?array
    {
        if (!is_string($value)) {
            return null;
        }

        return explode(',', $value);
    }
}
