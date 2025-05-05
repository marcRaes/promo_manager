<?php

namespace App\EventListener;

use App\Entity\Code;
use App\Repository\CodeRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

#[AsDoctrineListener(event: Events::postPersist, priority: 0, connection: 'default')]
class UserRolePromotion
{
    public function __construct(
        private CodeRepository $codeRepository,
        private EntityManagerInterface $entityManager,
        private TokenStorageInterface $tokenStorage,
        private RequestStack $requestStack,
    ) {}

    public function postPersist(PostPersistEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();

        if (!$entity instanceof Code) {
            return;
        }

        $user = $entity->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        if (\in_array('ROLE_VIP', $user->getRoles(), true)) {
            return;
        }

        $codeCount = $this->codeRepository->count(['user' => $user]);

        if ($codeCount >= 5) {
            $user->setRoles(array_merge($user->getRoles(), ['ROLE_VIP']));
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);

            $this->requestStack->getSession()->set('vip_just_earned', true);
        }
    }
}
