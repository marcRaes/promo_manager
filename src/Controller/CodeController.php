<?php

namespace App\Controller;

use App\Entity\Code;
use App\Form\CodeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/code')]
final class CodeController extends AbstractController
{
    #[Route('/new', name: 'app_code_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        $code = new Code();
        $user = $this->getUser();
        $isVip = $authorizationChecker->isGranted('ROLE_VIP', $user);
        $form = $this->createForm(CodeForm::class, $code, [
            'is_vip_user' => $isVip
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $code->setUser($user);
            $code->setIsVipOnly($isVip);
            $entityManager->persist($code);
            $entityManager->flush();

            $session = $request->getSession();
            if ($session->get('vip_just_earned')) {
                $this->addFlash('info', '🎉 Félicitations ! Vous êtes désormais membre <strong>VIP</strong> !');
                $session->remove('vip_just_earned');
            }

            return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('code/new.html.twig', [
            'code' => $code,
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_code_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Code $code, EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        $user = $this->getUser();
        $isVip = $authorizationChecker->isGranted('ROLE_VIP', $user);
        $form = $this->createForm(CodeForm::class, $code, [
            'is_vip_user' => $isVip
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $code->setUser($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('code/edit.html.twig', [
            'code' => $code,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_code_delete', methods: ['POST'])]
    public function delete(Request $request, Code $code, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$code->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($code);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
    }
}
