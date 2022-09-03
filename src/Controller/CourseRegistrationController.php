<?php
namespace App\Controller;

use function date;
use Exception;
use App\Dto\ValidationErrorDto;
use App\Dto\CreateCourseRegistrationDto;
use App\Entity\CourseRegistration;
use App\Repository\CourseRepository;
use App\Repository\StudentRepository;
use App\Repository\TimetableRepository;
use App\Repository\CourseRegistrationRepository;
use App\Security\JwtAuth;
use App\Security\VoterAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/course-registrations', name: 'course_registration_')]
class CourseRegistrationController extends AbstractController {
  public function __construct(
    private readonly ValidatorInterface $validator,
    private readonly SerializerInterface $serializer,
    private readonly CourseRepository $courseRepository,
    private readonly StudentRepository $studentRepository,
    private readonly TimetableRepository $timetableRepository,
    private readonly CourseRegistrationRepository $courseRegistrationRepository,
  ) {}

  #[Route('', name: 'create', methods: ['POST']), JwtAuth]
  public function create(Request $request): JsonResponse {
    $courseRegistration = new CourseRegistration;

    $this->denyAccessUnlessGranted(VoterAction::CREATE, $courseRegistration);

    try {
      $courseRegistrationDto = $this->serializer->deserialize(
        $request->getContent(),
        CreateCourseRegistrationDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $courseRegistrationDto = new CreateCourseRegistrationDto;
    }

    $errors = $this->validator->validate($courseRegistrationDto);

    if (count($errors) > 0) {
      $errorsList = ValidationErrorDto::listOf($errors);
      return $this->json(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    $courseRegistration->session = (int) date('Y');
    $courseRegistration->course = $this->courseRepository->find($courseRegistrationDto->courseId);
    $courseRegistration->student = $this->studentRepository->find($courseRegistrationDto->studentId);

    $this->courseRegistrationRepository->save($courseRegistration);

    return $this->json(
      ['data' => $courseRegistration],
      Response::HTTP_CREATED,
      context: [
        'groups' => [
          'course_registration',
          'course_registration_course',
          'course_registration_student',
          'course',
          'student'
        ],
      ]
    );
  }

  #[Route('/{id}', name: 'delete', methods: ['DELETE']), JwtAuth]
  public function delete(int $id): JsonResponse {
    $courseRegistration = $this->courseRegistrationRepository->find($id);

    if ($courseRegistration === null) {
      return $this->json(['error' => 'Course registration not found'], Response::HTTP_NOT_FOUND);
    }

    $this->denyAccessUnlessGranted(VoterAction::DELETE, $courseRegistration);

    $timetable = $this->timetableRepository->findOneBy([
      'session' => $courseRegistration->session,
      'semester' => $courseRegistration->course->semester
    ]);

    if (!empty($timetable)) {
      return $this->json(['error' => 'A timetable has already been created'], Response::HTTP_FORBIDDEN);
    }
    
    $this->courseRegistrationRepository->delete($courseRegistration);

    return $this->json(null, Response::HTTP_NO_CONTENT);
  }
}
