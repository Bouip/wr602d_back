<?php

namespace App\Controller;

use App\Repository\ScoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ScoreShareController extends AbstractController
{
    #[Route('/score/share/{token}', name: 'app_score_share')]
    public function share(string $token, ScoreRepository $scoreRepository): Response
    {
        $score = $scoreRepository->findOneBy(['shareToken' => $token]);

        if (!$score) {
            throw $this->createNotFoundException('Score introuvable');
        }

        return $this->render('score/share.html.twig', [
            'score' => $score,
        ]);
    }
}