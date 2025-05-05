<?php

namespace App\Validator;

use App\Entity\Code;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class UniquePromoCodeValidator extends ConstraintValidator
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniquePromoCode || !$value instanceof Code) {
            return;
        }

        $codeRepository = $this->entityManager->getRepository(Code::class);
        $existing = $codeRepository->findOneBy([
            'name' => $value->getName(),
            'domainName' => $value->getDomainName(),
            'validFrom' => $value->getValidFrom(),
            'validUntil' => $value->getValidUntil(),
        ]);

        if ($existing && $existing->getId() !== $value->getId()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ name }}', $value->getName())
                ->addViolation();
        }
    }
}
