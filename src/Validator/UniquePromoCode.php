<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class UniquePromoCode extends Constraint
{
    public string $message = 'Un code promo "{{ name }}" existe déjà pour cette période.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
