<?php

namespace App\Validator;

use App\Entity\Code;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class NotExpiredPromoCodeValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof NotExpiredPromoCode || !$value instanceof Code) {
            return;
        }

        $now = new \DateTimeImmutable('today');

        if ($value->getValidUntil() < $now) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ name }}', $value->getName())
                ->setParameter('{{ date }}', $value->getValidUntil()->format('d/m/Y'))
                ->addViolation();
        }
    }
}
