<?php

namespace App\Controller;

use App\Repository\CodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CodeRepository $codeRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'codes' => $codeRepository->findValidCodes($this->getUser()),
            'user' => $this->getUser(),
        ]);
    }
}
