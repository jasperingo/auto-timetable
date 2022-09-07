<?php
namespace App\Controller;

use function date;
use function count;
use function usort;
use function array_map;
use DateTime;
use Exception;
use App\Security\JwtAuth;
use App\Entity\Course;
use App\Entity\StaffRole;
use App\Entity\Timetable;
use App\Entity\Examination;
use App\Security\VoterAction;
use App\Dto\CreateTimetableDto;
use App\Dto\ValidationErrorDto;
use App\Repository\HallRepository;
use App\Repository\StaffRepository;
use App\Repository\CourseRepository;
use App\Repository\TimetableRepository;
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
    private readonly HallRepository $hallRepository,
    private readonly StaffRepository $staffRepository,
    private readonly CourseRepository $courseRepository,
    private readonly TimetableRepository $timetableRepository,
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

    // TODO: COMPUTE TIMETABLE...
    
    return $this->json(
      [
        'data' => $timetable, 
        'halls' => $halls,
        'examinations' => $examinations, 
      ], 
      Response::HTTP_CREATED,
      context: ['groups' => ['timetable', 'examination', 'course', 'examination_course', 'hall']]
    );
  }
}
