<?php

namespace App\Controller;

use App\Entity\Code;
use App\Form\CodeForm;
use App\Service\CodeFormService;
use App\Service\PromoCodeService;
use App\Service\VipService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/code')]
final class CodeController extends AbstractController
{
    public function __construct(
        private readonly PromoCodeService $promoCodeService,
        private readonly VipService $vipService,
        private readonly CodeFormService $codeFormService,
        private readonly Security $security,
    ) {}
    #[Route('/new', name: 'app_code_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $code = new Code();
        $user = $this->security->getUser();

        $form = $this->codeFormService->createForm(CodeForm::class, $code, $user);

        if ($this->codeFormService->handle($form, $request)) {
            $this->promoCodeService->create($code, $user);
            $this->vipService->handleVipFlash();

            return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('code/new.html.twig', [
            'code' => $code,
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_code_edit', methods: ['GET', 'POST'])]
    public function edit(Code $code, Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->codeFormService->createForm(CodeForm::class, $code, $user);

        if ($this->codeFormService->handle($form, $request)) {
            $this->promoCodeService->update($code, $user);

            return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('code/edit.html.twig', [
            'code' => $code,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_code_delete', methods: ['POST'])]
    public function delete(Request $request, Code $code): Response
    {
        if ($this->isCsrfTokenValid('delete'.$code->getId(), $request->getPayload()->getString('_token'))) {
            $this->promoCodeService->delete($code);
        }

        return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
    }
}
