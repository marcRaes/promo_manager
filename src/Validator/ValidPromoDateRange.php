<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class ValidPromoDateRange extends Constraint
{
    public string $message = 'La date de début ({{ from }}) ne peut pas être postérieure à la date de fin ({{ until }}).';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
