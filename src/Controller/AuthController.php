<?php
namespace App\Controller;

use App\Entity\Staff;
use App\Entity\Student;
use App\Service\JwtService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auth', name: 'auth_')]
class AuthController extends AbstractController {
  #[Route('/staff', name: 'staff', methods: ['POST'])]
  public function staff(JwtService $jwtService): JsonResponse {
    $user = (object) $this->getUser();

    return $this->json(['data' => [
      'staffId' => $user->id,
      'accessToken' => $jwtService->sign($user->id, Staff::class),
    ]]);
  }

  #[Route('/student', name: 'student', methods: ['POST'])]
  public function student(JwtService $jwtService): JsonResponse {
    $user = (object) $this->getUser();

    return $this->json(['data' => [
      'studentId' => $user->id,
      'accessToken' => $jwtService->sign($user->id, Student::class),
    ]]);
  }
}
