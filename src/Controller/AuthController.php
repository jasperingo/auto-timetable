<?php
namespace App\Controller;

use App\Entity\Staff;
use App\Service\JwtService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auth', name: 'auth_')]
class AuthController extends AbstractController {
  #[Route('/staff', name: 'staff', methods: ['POST'])]
  public function create(JwtService $jwtService): JsonResponse {
    $user = (object) $this->getUser();

    return $this->json(['data' => [
      'userId' => $user->id,
      'accessToken' => $jwtService->sign($user->id, Staff::class),
    ]]);
  }
}
