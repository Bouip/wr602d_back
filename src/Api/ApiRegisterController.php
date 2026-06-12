<?php

namespace App\Api;

use App\Entity\User;
use App\HttpClient\MailerClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiRegisterController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private SerializerInterface $serializer,
        private MailerClient $mailerClient,
    ) {}

    #[Route(
        '/api/user/register',
        name: 'api_register',
        defaults: [
            '_api_resource_class' => User::class,
            '_api_item_operation_name' => 'api_register'
        ],
        methods: ['POST']
    )]
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['plainPassword'], $data['username'])) {
            return new JsonResponse(['message' => 'Missing required fields'], 400);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $data['plainPassword'])
        );
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        try {
            $this->mailerClient->sendEmail(
                $data['email'],
                'Bienvenue sur Vroum Vroum !',
                sprintf(
                    'Bonjour %s, bienvenue sur Vroum Vroum ! Ton compte a bien été créé. Bonne course !',
                    $data['username']
                )
            );
        } catch (\Exception $e) {
            // bloque pas l'inscription si le mail échoue
        }

        return new JsonResponse(['message' => 'User created successfully'], 201);
    }
}