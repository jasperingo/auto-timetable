<?php
namespace App\Controller;

use DateTime;
use Exception;
use App\Dto\UpdatePasswordDto;
use App\Dto\CreateStudentDto;
use App\Dto\ValidationErrorDto;
use App\Entity\Student;
use App\Entity\CourseRegistration;
use App\Security\JwtAuth;
use App\Security\VoterAction;
use App\Repository\CourseRepository;
use App\Repository\DepartmentRepository;
use App\Repository\StudentRepository;
use App\Repository\CourseRegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/students', name: 'student_')]
class StudentController extends AbstractController {
  public function __construct(
    private readonly StudentRepository $studentRepository,
    private readonly ValidatorInterface $validator,
    private readonly SerializerInterface $serializer,
    private readonly CourseRepository $courseRepository,
    private readonly DepartmentRepository $departmentRepository,
    private readonly UserPasswordHasherInterface $passwordHasher,
    private readonly CourseRegistrationRepository $courseRegistrationRepository,
  ) {}

  #[Route('', name: 'create', methods: ['POST']), JwtAuth]
  public function create(Request $request): JsonResponse {
    $student = new Student;

    $this->denyAccessUnlessGranted(VoterAction::CREATE, $student);

    try {
      $studentDto = $this->serializer->deserialize(
        $request->getContent(),
        CreateStudentDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $studentDto = new CreateStudentDto;
    }

    $errors = $this->validator->validate($studentDto);

    if (count($errors) > 0) {
      $errorsList = ValidationErrorDto::listOf($errors);
      return $this->json(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    $student->createdAt = new DateTime;
    $student->joinedAt = $studentDto->joinedAt;
    $student->lastName = $studentDto->lastName;
    $student->firstName = $studentDto->firstName;
    $student->matriculationNumber = $studentDto->matriculationNumber;
    $student->password = $this->passwordHasher->hashPassword($student, $studentDto->password);
    $student->department = $this->departmentRepository->find($studentDto->departmentId);

    $this->studentRepository->save($student);

    return $this->json(
      ['data' => $student],
      Response::HTTP_CREATED,
      context: ['groups' => ['student', 'student_department', 'department']]
    );
  }

  #[
    Route('/{id}/password', name: 'update_password', requirements: ['id' => '\d+'], methods: ['PUT']),
    JwtAuth
  ]
  public function updatePassword(Request $request, int $id): JsonResponse {
    $student = $this->studentRepository->find($id);

    if ($student === null) {
      return $this->json(['error' => 'Student not found'], Response::HTTP_NOT_FOUND);
    }

    $this->denyAccessUnlessGranted(VoterAction::UPDATE, $student);

    try {
      $passwordDto = $this->serializer->deserialize(
        $request->getContent(),
        UpdatePasswordDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $passwordDto = new UpdatePasswordDto;
    }

    $errors = $this->validator->validate($passwordDto);

    if (count($errors) > 0) {
      $errorsList = ValidationErrorDto::listOf($errors);
      return $this->json(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    $student->password = $this->passwordHasher->hashPassword($student, $passwordDto->password);

    $this->studentRepository->save($student);

    return $this->json(
      ['data' => $student],
      context: ['groups' => ['student', 'student_department', 'department']]
    );
  }

  #[
    Route('/{id}', name: 'read', requirements: ['id' => '\d+'], methods: ['GET']),
    JwtAuth
  ]
  public function read(int $id): JsonResponse {
    $student = $this->studentRepository->find($id);

    if ($student === null) {
      return $this->json(['error' => 'Student not found'], Response::HTTP_NOT_FOUND);
    }

    $this->denyAccessUnlessGranted(VoterAction::READ, $student);

    return $this->json(
      ['data' => $student],
      context: ['groups' => ['student', 'student_department', 'department']]
    );
  }

  #[Route('', name: 'read_many', methods: ['GET']), JwtAuth]
  public function readMany(Request $request): JsonResponse {
    $this->denyAccessUnlessGranted(VoterAction::READ_MANY, new Student);

    $criteria = [];

    $joinedAt = $request->query->get('session');

    $departmentId = $request->query->get('departmentId');

    if (!empty($joinedAt)) $criteria['joinedAt'] = $joinedAt;

    if (!empty($departmentId)) $criteria['department'] = $departmentId;

    $students = $this->studentRepository->findBy($criteria);

    return $this->json(
      ['data' => $students],
      context: ['groups' => ['student', 'student_department', 'department']]
    );
  }
  
  #[
    Route(
      '/{id}/course-registrations',
      name: 'read_many_course_registrations',
      requirements: ['id' => '\d+'],
      methods: ['GET'],
    ),
    JwtAuth
  ]
  public function readManyCourseRegistrations(Request $request, int $id): JsonResponse {
    $student = $this->studentRepository->find($id);

    if ($student === null) {
      return $this->json(['error' => 'Student not found'], Response::HTTP_NOT_FOUND);
    }

    $courseRegistration = new CourseRegistration;
    $courseRegistration->student = $student;

    $this->denyAccessUnlessGranted(VoterAction::READ_MANY, $courseRegistration);

    $courseRegistrations = $this->courseRegistrationRepository->findAllByStudentIdAndSessionAndSemester(
      $student->id,
      $request->query->get('session'),
      $request->query->get('semester'),
    );

    return $this->json(
      ['data' => $courseRegistrations],
      context: ['groups' => ['course_registration', 'course_registration_course', 'course']]
    );
  }
}
