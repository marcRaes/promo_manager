<?php

namespace App\Controller;

use App\Repository\CodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard')]
final class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(CodeRepository $codeRepository): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'user' => $this->getUser(),
            'codes' => $codeRepository->findBy(['user' => $this->getUser()]),
        ]);
    }
}
