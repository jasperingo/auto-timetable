<?php
namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Staff;
use App\Security\JwtAuth;
use App\Security\VoterAction;
use App\Dto\CreateStaffDto;
use App\Dto\ValidationErrorDto;
use App\Dto\UpdateStaffPasswordDto;
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

    try {
      $staffDto = $this->serializer->deserialize(
        $request->getContent(),
        CreateStaffDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $staffDto = new CreateStaffDto;
    }

    $errors = $this->validator->validate($staffDto);

    if (count($errors) > 0) {
      $errorsList = ValidationErrorDto::listOf($errors);
      return $this->json(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    $staff->role = $staffDto->role;
    $staff->createdAt = new DateTime;
    $staff->title = $staffDto->title;
    $staff->lastName = $staffDto->lastName;
    $staff->firstName = $staffDto->firstName;
    $staff->staffNumber = $staffDto->staffNumber;
    $staff->password = $this->passwordHasher->hashPassword($staff, $staffDto->password);

    if ($staffDto->departmentId !== null) {
      $staff->department = $this->departmentRepository->find($staffDto->departmentId);
    }

    $this->staffRepository->save($staff);

    return $this->json(['data' => $staff], Response::HTTP_CREATED);
  }

  #[
    Route(
      '/{id}/password',
      name: 'update_password',
      requirements: ['id' => '\d+'],
      methods: ['PUT']
    ),
    JwtAuth
  ]
  public function updatePassword(Request $request, int $id): JsonResponse {
    $staff = $this->staffRepository->find($id);

    if ($staff === null) {
      return $this->json(['error' => 'Staff not found'], Response::HTTP_NOT_FOUND);
    }

    $this->denyAccessUnlessGranted(VoterAction::UPDATE, $staff);

    try {
      $passwordDto = $this->serializer->deserialize(
        $request->getContent(),
        UpdateStaffPasswordDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $passwordDto = new UpdateStaffPasswordDto;
    }

    $errors = $this->validator->validate($passwordDto);

    if (count($errors) > 0) {
      $errorsList = ValidationErrorDto::listOf($errors);
      return $this->json(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    $staff->password = $this->passwordHasher->hashPassword($staff, $passwordDto->password);

    $this->staffRepository->save($staff);

    return $this->json(['data' => $staff]);
  }

  #[
    Route(
      '/{id}',
      name: 'read',
      requirements: ['id' => '\d+'],
      methods: ['GET']
    ),
    JwtAuth
  ]
  public function read(int $id): JsonResponse {
    $staff = $this->staffRepository->find($id);

    if ($staff === null) {
      return $this->json(['error' => 'Staff not found'], Response::HTTP_NOT_FOUND);
    }

    $this->denyAccessUnlessGranted(VoterAction::READ, $staff);

    return $this->json(['data' => $staff]);
  }

  #[
    Route(
      '',
      name: 'read-many',
      methods: ['GET']
    ),
    JwtAuth
  ]
  public function readMany(): JsonResponse {
    $this->denyAccessUnlessGranted(VoterAction::READ_MANY, new Staff);

    $staffs = $this->staffRepository->findAll();

    return $this->json(['data' => $staffs]);
  }
}
