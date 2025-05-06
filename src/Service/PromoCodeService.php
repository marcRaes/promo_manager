<?php

namespace App\Service;

use App\Entity\Code;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class PromoCodeService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RequestStack $requestStack,
    ) {}

    public function create(Code $code, UserInterface $user): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Les instances de "%s" ne sont pas prises en charge.', $user::class));
        }

        $code->setUser($user);
        $this->entityManager->persist($code);
        $this->entityManager->flush();

        $this->addFlash('Code promo créé avec succès.');
    }

    public function update(Code $code, UserInterface $user): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Les instances de "%s" ne sont pas prises en charge.', $user::class));
        }

        $code->setUser($user);
        $this->entityManager->flush();

        $this->addFlash('Code promo mis à jour.');
    }

    public function delete(Code $code): void
    {
        $this->entityManager->remove($code);
        $this->entityManager->flush();

        $this->addFlash('Code promo supprimé.');
    }

    private function addFlash(string $message): void
    {
        $this->requestStack->getSession()?->getFlashBag()->add('success', $message);
    }
}
