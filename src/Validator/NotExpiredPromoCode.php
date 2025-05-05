<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class NotExpiredPromoCode extends Constraint
{
    public string $message = 'Le code promo "{{ name }}" a une date de fin déjà dépassée ({{ date }}).';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
