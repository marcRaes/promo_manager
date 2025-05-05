<?php

namespace App\Validator;

use App\Entity\Code;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class ValidPromoDateRangeValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidPromoDateRange || !$value instanceof Code) {
            return;
        }

        $validFrom = $value->getValidFrom();
        $validUntil = $value->getValidUntil();

        if ($validFrom && $validUntil && $validFrom > $validUntil) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ from }}', $validFrom->format('d/m/Y'))
                ->setParameter('{{ until }}', $validUntil->format('d/m/Y'))
                ->addViolation();
        }
    }
}
