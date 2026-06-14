<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AdminAuthController extends AbstractController
{
    #[Route('/admin/jwt-login', name: 'app_admin_jwt_login')]
    public function jwtLogin(
        Request $request,
        UserRepository $userRepository,
        JWTTokenManagerInterface $jwtManager,
        TokenStorageInterface $tokenStorage
    ): Response {
        $token = $request->query->get('token');

        if (!$token) {
            return $this->redirectToRoute('app_login');
        }

        try {
            $payload = $jwtManager->parse($token);
            $email = $payload['email'] ?? null;

            if (!$email) {
                return $this->redirectToRoute('app_login');
            }

            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user || !in_array('ROLE_ADMIN', $user->getRoles())) {
                return $this->redirectToRoute('app_login');
            }

            $symfonyToken = new UsernamePasswordToken($user, 'main', $user->getRoles());
            $tokenStorage->setToken($symfonyToken);
            $request->getSession()->set('_security_main', serialize($symfonyToken));

            return $this->redirectToRoute('app_admin');

        } catch (\Exception) {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->redirect('http://localhost:5173');
    }
}