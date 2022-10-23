<?php
namespace App\Controller;

use function date;
use function count;
use function usort;
use function array_map;
use DateTime;
use Exception;
use App\Security\JwtAuth;
use App\Entity\Hall;
use App\Entity\Course;
use App\Entity\StaffRole;
use App\Entity\Timetable;
use App\Entity\Examination;
use App\Entity\ExaminationHall;
use App\Security\VoterAction;
use App\Dto\CreateTimetableDto;
use App\Dto\ValidationErrorDto;
use App\Service\TimetableService;
use App\Repository\HallRepository;
use App\Repository\StaffRepository;
use App\Repository\CourseRepository;
use App\Repository\TimetableRepository;
use App\Repository\ExaminationRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

#[Route('/timetables', name: 'timetable_')]
class TimetableController extends AbstractController {
  public function __construct(
    private readonly ValidatorInterface $validator,
    private readonly SerializerInterface $serializer,
    private readonly TimetableService $timetableService,
    private readonly HallRepository $hallRepository,
    private readonly StaffRepository $staffRepository,
    private readonly CourseRepository $courseRepository,
    private readonly TimetableRepository $timetableRepository,
    private readonly ExaminationRepository $examinationRepository,
  ) {}

  #[Route('', name: 'create', methods: ['POST']), JwtAuth]
  public function create(Request $request): JsonResponse {
    $timetable = new Timetable;
  
    $this->denyAccessUnlessGranted(VoterAction::CREATE, $timetable);

    try {
      $timetableDto = $this->serializer->deserialize(
        $request->getContent(),
        CreateTimetableDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $timetableDto = new CreateTimetableDto;
    }

    $errors = $this->validator->validate($timetableDto);

    if (count($errors) > 0) {
      return $this->json(
        ['errors' => ValidationErrorDto::listOf($errors)], 
        Response::HTTP_BAD_REQUEST
      );
    }

    $timetable->session = (int) date('Y');
    $timetable->createdAt = new DateTime;
    $timetable->semester = $timetableDto->semester;

    $halls = $this->hallRepository->findBy([], ['capacity' => 'DESC']);

    $invigilators = $this->staffRepository->findBy(['role' => StaffRole::Invigilator]);

    $courses = $this->courseRepository->findBy(['semester' => $timetable->semester]);

    $criteria = Criteria::create()->where(Criteria::expr()->eq("session", $timetable->session));

    $examinations = array_map(function(Course $course) use ($criteria) {
      $exam = new Examination;
      $exam->course = $course;
      $exam->halls = [];
      $exam->invigilators = [];
      $exam->numberOfStudents = $course->courseRegistrations->matching($criteria)->count();
      return $exam;
    }, $courses);

    usort(
      $examinations, 
      fn(Examination $exam1, Examination $exam2) => 
        ($exam1->numberOfStudents === $exam2->numberOfStudents) 
          ? 0 
          : (($exam1->numberOfStudents < $exam2->numberOfStudents) ? 1 : -1)
    );

    foreach($examinations as $examination) {
      $examCapacityVacancy = $this->timetableService->getExamCapacityVacancy($examination);

      if ($examCapacityVacancy <= 0) {
        continue;
      }

      foreach($halls as $hall) {
        error_log($examCapacityVacancy);

        if ($examCapacityVacancy <= 0) {
          break;
        }
        
        $hallVacancy = $this->timetableService->getHallVacancy($hall, $examinations);
        
        if ($hallVacancy <= 0) {
          continue;
        } else {
          $examHall = new ExaminationHall;
          $examHall->hall = $hall;
          $examHall->capacity = ($hallVacancy >= $examCapacityVacancy) 
            ? $examCapacityVacancy 
            : $hallVacancy;
          
          $examCapacityVacancy -= $examHall->capacity;

          $examination->halls[] = $examHall;
        }
      }
    }

    // TODO: COMPUTE TIMETABLE...
    
    return $this->json(
      [
        'data' => $timetable, 
        // 'halls' => $halls,
        'examinations' => $examinations, 
      ], 
      Response::HTTP_CREATED,
      context: ['groups' => [
        'timetable', 
        'examination', 
        'course', 
        'examination_course', 
        'examination_halls', 
        'examination_hall',
        'examination_hall_hall',
        'hall',
        ]
      ]
    );
  }

  #[
    Route('/{id}', name: 'read', requirements: ['id' => '\d+'], methods: ['GET']),
    JwtAuth
  ]
  public function read(int $id): JsonResponse {
    $timetable = $this->timetableRepository->find($id);

    if ($timetable === null) {
      return $this->json(['error' => 'Timetable not found'], Response::HTTP_NOT_FOUND);
    }

    return $this->json(
      ['data' => $timetable],
      context: ['groups' => [
          'timetable', 
          'timetable_examinations', 
          'examination', 
          'examination_course', 
          'course'
        ]
      ]
    );
  }

  #[
    Route(
      '/examination/{id}', 
      name: 'read_examination', 
      requirements: ['id' => '\d+'], 
      methods: ['GET']
    ),
    JwtAuth
  ]
  public function readExamination(int $id): JsonResponse {
    $examination = $this->examinationRepository->find($id);

    if ($examination === null) {
      return $this->json(['error' => 'Examination not found'], Response::HTTP_NOT_FOUND);
    }

    return $this->json(
      ['data' => $examination],
      context: ['groups' => [
          'examination', 
          'examination_course', 
          'course',
          'examination_halls',
          'examination_hall',
          'examination_hall_hall',
          'hall',
          'examination_invigilators',
          'examination_invigilator',
          'examination_invigilator_staff',
          'staff'
        ]
      ]
    );
  }

  #[Route('', name: 'read_many', methods: ['GET']), JwtAuth]
  public function readMany(): JsonResponse {
    $timetables = $this->timetableRepository->findAll();

    return $this->json(
      ['data' => $timetables],
      context: ['groups' => ['timetable']]
    );
  }
}
