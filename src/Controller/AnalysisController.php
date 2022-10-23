<?php
namespace App\Controller;

use App\Entity\Analysis;
use App\Security\JwtAuth;
use App\Security\VoterAction;
use App\Repository\HallRepository;
use App\Repository\StaffRepository;
use App\Repository\CourseRepository;
use App\Repository\StudentRepository;
use App\Repository\DepartmentRepository;
use App\Repository\TimetableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/analysis', name: 'analysis_')]
class AnalysisController extends AbstractController {
  public function __construct(
    private readonly HallRepository $hallRepository,
    private readonly StaffRepository $staffRepository,
    private readonly CourseRepository $courseRepository,
    private readonly StudentRepository $studentRepository,
    private readonly TimetableRepository $timetableRepository,
    private readonly DepartmentRepository $departmentRepository,
  ) {}

  #[
    Route('', name: 'read', methods: ['GET']),
    JwtAuth
  ]
  public function readMany(): JsonResponse {
    $analysis = new Analysis;

    $this->denyAccessUnlessGranted(VoterAction::READ, $analysis);

    $analysis->halls = $this->hallRepository->count([]);
    $analysis->courses = $this->courseRepository->count([]);
    $analysis->staffs = $this->staffRepository->count([]);
    $analysis->students = $this->studentRepository->count([]);
    $analysis->departments = $this->departmentRepository->count([]);
    $analysis->timetables = $this->timetableRepository->count([]);

    return $this->json(['data' => $analysis]);
  }
}
