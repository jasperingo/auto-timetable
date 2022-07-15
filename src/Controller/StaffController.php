<?php
namespace App\Controller;

use DateTime;
use App\Entity\Staff;
use App\Entity\StaffRole;
use App\Dto\CreateStaffDto;
use App\Repository\StaffRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/staff', name: 'staff_')]
class StaffController extends AbstractController {
  public function __construct(
    private readonly StaffRepository $staffRepository,
    private readonly ValidatorInterface $validator,
    private readonly SerializerInterface $serializer,
    private readonly UserPasswordHasherInterface $passwordHasher,
  ) {}

  #[Route('', name: 'create', methods: ['POST'])]
  public function create(Request $request): JsonResponse  {
    $staffDto = $this->serializer->deserialize(
      $request->getContent(),
      CreateStaffDto::class,
      JsonEncoder::FORMAT,
    );

    $errors = $this->validator->validate($staffDto);

    if (count($errors) > 0) {
      $errorsList = [];
      foreach ($errors as $error) {
        $errorsList[] = [
          'name' => $error->getPropertyPath(),
          'value' => $error->getInvalidValue(),
          'message' => $error->getMessage(),
        ];
      }
      return new JsonResponse(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    $staff = new Staff;
    $staff->role = StaffRole::Admin;
    $staff->createdAt = new DateTime;
    $staff->title = $staffDto->title;
    $staff->firstName = $staffDto->firstName;
    $staff->lastName = $staffDto->lastName;
    $staff->staffNumber = $staffDto->staffNumber;
    $staff->password = $this->passwordHasher->hashPassword($staff, $staffDto->password);

    $this->staffRepository->save($staff);

    return new JsonResponse(['data' => $staff], Response::HTTP_CREATED);
  }
}
