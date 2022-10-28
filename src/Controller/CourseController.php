<?php
namespace App\Controller;

use Exception;
use App\Entity\Student;
use App\Entity\Course;
use App\Security\JwtAuth;
use App\Security\VoterAction;
use App\Security\JwtOptionalAuth;
use App\Dto\UpdateCourseDto;
use App\Dto\CreateCourseDto;
use App\Dto\ValidationErrorDto;
use App\Repository\CourseRepository;
use App\Repository\DepartmentRepository;
use App\Repository\CourseRegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/courses', name: 'course_')]
class CourseController extends AbstractController {
  public function __construct(
    private readonly ValidatorInterface $validator,
    private readonly SerializerInterface $serializer,
    private readonly CourseRepository $courseRepository,
    private readonly DepartmentRepository $departmentRepository,
    private readonly CourseRegistrationRepository $courseRegistrationRepository,
  ) {}

  #[Route('', name: 'create', methods: ['POST']), JwtAuth]
  public function create(Request $request): JsonResponse {
    $course = new Course;

    $this->denyAccessUnlessGranted(VoterAction::CREATE, $course);

    try {
      $courseDto = $this->serializer->deserialize(
        $request->getContent(),
        CreateCourseDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $courseDto = new CreateCourseDto;
    }

    $errors = $this->validator->validate($courseDto);

    if (count($errors) > 0) {
      $errorsList = ValidationErrorDto::listOf($errors);
      return $this->json(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    $course->title = $courseDto->title;
    $course->code = $courseDto->code;
    $course->level = $courseDto->level;
    $course->semester = $courseDto->semester;
    $course->department = $this->departmentRepository->find($courseDto->departmentId);

    $this->courseRepository->save($course);

    return $this->json(
      ['data' => $course],
      Response::HTTP_CREATED,
      context: ['groups' => ['course', 'course_department', 'department']]
    );
  }

  #[
    Route('/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['PUT']),
    JwtAuth
  ]
  public function update(Request $request, int $id): JsonResponse {
    $course = (object) $this->courseRepository->find($id);

    if ($course === null) {
      return $this->json(['error' => 'Course not found'], Response::HTTP_NOT_FOUND);
    }

    $this->denyAccessUnlessGranted(VoterAction::UPDATE, $course);

    try {
      $courseDto = $this->serializer->deserialize(
        $request->getContent(),
        UpdateCourseDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $courseDto = new UpdateCourseDto;
    }

    $errors = $this->validator->validate($courseDto);

    if (count($errors) > 0) {
      $errorsList = ValidationErrorDto::listOf($errors);
      return $this->json(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    if ($courseDto->title !== null) {
      $course->title = $courseDto->title;
    }

    if ($courseDto->code !== null) {
      $course->code = $courseDto->code;
    }

    $this->courseRepository->save($course);

    return $this->json(
      ['data' => $course],
      context:  ['groups' => ['course', 'course_department', 'department']]
    );
  }

  #[Route('/{id}', name: 'read', methods: ['GET'])]
  public function read(int $id): JsonResponse {
    $course = $this->courseRepository->find($id);

    if ($course === null) {
      return $this->json(['error' => 'Course not found'], Response::HTTP_NOT_FOUND);
    }

    return $this->json(
      ['data' => $course],
      context:  ['groups' => ['course', 'course_department', 'department']]
    );
  }

  #[Route('', name: 'read_many', methods: ['GET']), JwtOptionalAuth]
  public function readMany(Request $request): JsonResponse {
    $user = $this->getUser();

    if ($user instanceof Student) {

      $year = (int) date('Y');

      $studentLevel = ($year - $user->joinedAt) + 1;

      $level = $request->query->get('level');
      $semester = $request->query->get('semester');
      $departmentId = $request->query->get('departmentId');

      $courses = $this->courseRepository->findAllByStudentLevelAndDepartmentIdAndLevelAndSemester(
        $studentLevel,
        $level,
        $semester,
        $departmentId,
      );
      
      foreach ($courses as $course) {
        $courseRegistration = $this->courseRegistrationRepository->findOneBy([
          'student' => $user->id, 
          'course' => $course->id, 
          'session' => $year
        ]);

        $course->courseRegistrations = empty($courseRegistration) ? [] : [$courseRegistration];
      }

      return $this->json(
        ['data' => $courses],
          context: ['groups' => [
            'course', 
            'course_department', 
            'department', 
            'course_registrations', 
            'course_registration'
          ]
        ]
      );
    }
  
    $semester = $request->query->get('semester');

    $departmentId = $request->query->get('departmentId');

    $criteria = [];
    
    if (!empty($semester)) $criteria['semester'] = $semester;

    if (!empty($departmentId)) $criteria['department'] = $departmentId;

    $courses = $this->courseRepository->findBy($criteria);

    return $this->json(
      ['data' => $courses],
      context: ['groups' => ['course', 'course_department', 'department']]
    );
  }
}
