<?php
namespace App\Controller;

use App\Security\JwtAuth;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Route('/timetables', name: 'timetable_')]
class TimetableController extends AbstractController {

  #[Route('', name: 'create', methods: ['POST']), JwtAuth]
  public function create(Request $request): JsonResponse {
    return $this->json(['data' => 'Timetable created'], Response::HTTP_CREATED);
  }
}
