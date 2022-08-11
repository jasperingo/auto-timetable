<?php
namespace App\Controller;

use DateTime;
use Exception;
use App\Dto\CreateStudentDto;
use App\Dto\ValidationErrorDto;
use App\Entity\Student;
use App\Security\JwtAuth;
use App\Repository\DepartmentRepository;
use App\Repository\StudentRepository;
use App\Security\VoterAction;
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
    private readonly DepartmentRepository $departmentRepository,
    private readonly UserPasswordHasherInterface $passwordHasher,
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
}
