<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class ApiDocController extends AbstractController
{
    #[Route('/api/doc', name: 'api_doc')]
    public function index(): Response
    {
        return $this->render('swagger-ui.html.twig', [
            'title' => 'Hotel Management API Documentation'
        ]);
    }

    #[Route('/api/doc.json', name: 'api_doc_json')]
    public function getOpenApiSpec(): JsonResponse
    {
        $openApiSpec = Yaml::parseFile($this->getParameter('kernel.project_dir') . '/swagger.yaml');
        return $this->json($openApiSpec);
    }
} 
