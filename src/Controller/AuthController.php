<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: 'api_')]
class AuthController extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(
        Request $request,
        UserService $userService,
        SerializerInterface $serializer
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!isset($data['email']) || !isset($data['password']) || !isset($data['first_name']) || !isset($data['last_name'])) {
                return $this->json([
                    'message' => 'Email, password, first_name, and last_name are required'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Check if user already exists
            if ($userService->findByEmail($data['email'])) {
                return $this->json([
                    'message' => 'User with this email already exists'
                ], Response::HTTP_CONFLICT);
            }

            $user = $userService->createUser(
                $data['email'],
                $data['password'],
                $data['first_name'],
                $data['last_name']
            );

            return $this->json([
                'message' => 'User registered successfully',
                'user' => $serializer->serialize($user, 'json', ['groups' => ['user:read']])
            ], Response::HTTP_CREATED);
            
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'An error occurred while registering the user',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(): JsonResponse
    {
        return $this->json(['message' => 'Missing credentials'], Response::HTTP_UNAUTHORIZED);
    }
}
