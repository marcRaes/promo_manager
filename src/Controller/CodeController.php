<?php

namespace App\Controller;

use App\Repository\CodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CodeController extends AbstractController
{
    #[Route('/code', name: 'app_code')]
    public function index(CodeRepository $codeRepository): Response
    {
        return $this->render('code/index.html.twig', [
            'codes' => $codeRepository->findAll(),
        ]);
    }
}
