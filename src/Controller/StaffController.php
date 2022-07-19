<?php
namespace App\Controller;

use DateTime;
use App\Entity\Staff;
use App\Security\JwtAuth;
use App\Security\VoterAction;
use App\Dto\CreateStaffDto;
use App\Dto\ValidationErrorDto;
use App\Repository\StaffRepository;
use App\Repository\DepartmentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/staffs', name: 'staff_')]
class StaffController extends AbstractController {
  public function __construct(
    private readonly StaffRepository $staffRepository,
    private readonly ValidatorInterface $validator,
    private readonly SerializerInterface $serializer,
    private readonly DepartmentRepository $departmentRepository,
    private readonly UserPasswordHasherInterface $passwordHasher,
  ) {}

  #[Route('', name: 'create', methods: ['POST']), JwtAuth]
  public function create(Request $request): JsonResponse  {
    $staff = new Staff;

    $this->denyAccessUnlessGranted(VoterAction::CREATE, $staff);

    $staffDto = $this->serializer->deserialize(
      $request->getContent(),
      CreateStaffDto::class,
      JsonEncoder::FORMAT,
    );

    $errors = $this->validator->validate($staffDto);

    if (count($errors) > 0) {
      $errorsList = ValidationErrorDto::listOf($errors);
      return new JsonResponse(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    $staff->role = $staffDto->role;
    $staff->createdAt = new DateTime;
    $staff->title = $staffDto->title;
    $staff->lastName = $staffDto->lastName;
    $staff->firstName = $staffDto->firstName;
    $staff->staffNumber = $staffDto->staffNumber;
    $staff->password = $this->passwordHasher->hashPassword($staff, $staffDto->password);

    $staff->department = $this->departmentRepository->find($staffDto->departmentId);

    $this->staffRepository->save($staff);

    return new JsonResponse(['data' => $staff], Response::HTTP_CREATED);
  }
}
