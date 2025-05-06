<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class VipService
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authChecker,
        private readonly RequestStack $requestStack
    ) {}

    public function isVip(UserInterface $user): bool
    {
        return $this->authChecker->isGranted('ROLE_VIP', $user);
    }

    public function handleVipFlash(): void
    {
        $session = $this->requestStack->getSession();

        if ($session->get('vip_just_earned')) {
            $session->getFlashBag()->add(
                'info',
                '🎉 Félicitations ! Vous êtes désormais membre <strong>VIP</strong> !'
            );

            $session->remove('vip_just_earned');
        }
    }
}
