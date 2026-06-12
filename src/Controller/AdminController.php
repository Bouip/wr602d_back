<?php

namespace App\Controller;

use App\Repository\ScoreRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin')]
    public function index(UserRepository $userRepository, ScoreRepository $scoreRepository): Response
    {
        $users = $userRepository->findAll();
        $scores = $scoreRepository->findBy([], ['points' => 'DESC'], 10);
        $totalUsers = count($users);
        $totalScores = $scoreRepository->count([]);
        $bestScore = $scoreRepository->findOneBy([], ['points' => 'DESC']);

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'scores' => $scores,
            'totalUsers' => $totalUsers,
            'totalScores' => $totalScores,
            'bestScore' => $bestScore,
        ]);
    }

    #[Route('/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/scores', name: 'app_admin_scores')]
    public function scores(ScoreRepository $scoreRepository): Response
    {
        $scores = $scoreRepository->findBy([], ['points' => 'DESC']);
        return $this->render('admin/scores.html.twig', [
            'scores' => $scores,
        ]);
    }
}