<?php

namespace App\Service;

use App\Entity\Code;
use App\Entity\User;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class CodeFormService
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private AuthorizationCheckerInterface $authorizationChecker,
    ) {}

    public function createForm(string $formType, Code $code, UserInterface $user): FormInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Les instances de "%s" ne sont pas prises en charge.', $user::class));
        }

        return $this->formFactory->create($formType, $code, [
            'is_vip_user' => $this->authorizationChecker->isGranted('ROLE_VIP', $user)
        ]);
    }

    public function handle(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);

        return $form->isSubmitted() && $form->isValid();
    }
}
