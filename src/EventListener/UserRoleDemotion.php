<?php

namespace App\EventListener;

use App\Entity\Code;
use App\Repository\CodeRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

#[AsDoctrineListener(event: Events::postRemove, priority: 0, connection: 'default')]
class UserRoleDemotion
{
    public function __construct(
        private CodeRepository $codeRepository,
        private EntityManagerInterface $entityManager,
        private TokenStorageInterface $tokenStorage,
    ) {}

    public function postRemove(PostRemoveEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();

        if (!$entity instanceof Code) {
            return;
        }

        $user = $entity->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        if (!\in_array('ROLE_VIP', $user->getRoles(), true)) {
            return;
        }

        $codeCount = $this->codeRepository->count(['user' => $user]);

        if ($codeCount < 5) {
            $user->setRoles(['ROLE_USER']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);
        }
    }
}
