<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/departments', name: 'department_')]
class DepartmentController extends AbstractController {
  #[Route('', name: 'create', methods: ['POST'])]
  public function create(Request $request): JsonResponse {
    return $this->json(['data' => 'Yam']);
  }
}
