<?php
namespace App\Controller;

use Exception;
use App\Entity\Hall;
use App\Security\JwtAuth;
use App\Security\VoterAction;
use App\Dto\UpdateHallDto;
use App\Dto\CreateHallDto;
use App\Dto\ValidationErrorDto;
use App\Repository\HallRepository;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/halls', name: 'hall_')]
class HallController extends AbstractController {
  public function __construct(
    private readonly ValidatorInterface $validator,
    private readonly SerializerInterface $serializer,
    private readonly HallRepository $hallRepository,
    private readonly DepartmentRepository $departmentRepository,
  ) {}

  #[Route('', name: 'create', methods: ['POST']), JwtAuth]
  public function create(Request $request): JsonResponse {
    $hall = new Hall;

    $this->denyAccessUnlessGranted(VoterAction::CREATE, $hall);

    try {
      $hallDto = $this->serializer->deserialize(
        $request->getContent(),
        CreateHallDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $hallDto = new CreateHallDto;
    }

    $errors = $this->validator->validate($hallDto);

    if (count($errors) > 0) {
      $errorsList = ValidationErrorDto::listOf($errors);
      return $this->json(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    $hall->name = $hallDto->name;
    $hall->capacity = $hallDto->capacity;

    if ($hallDto->departmentId !== null) {
      $hall->department = $this->departmentRepository->find($hallDto->departmentId);
    }

    $this->hallRepository->save($hall);

    return $this->json(
      ['data' => $hall],
      Response::HTTP_CREATED,
      context: ['groups' => ['hall', 'hall_department', 'department']]
    );
  }

  #[
    Route('/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['PUT']),
    JwtAuth
  ]
  public function update(Request $request, int $id): JsonResponse {
    $hall = $this->hallRepository->find($id);

    if ($hall === null) {
      return $this->json(['error' => 'Hall not found'], Response::HTTP_NOT_FOUND);
    }

    $this->denyAccessUnlessGranted(VoterAction::UPDATE, $hall);

    try {
      $hallDto = $this->serializer->deserialize(
        $request->getContent(),
        UpdateHallDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $hallDto = new UpdateHallDto;
    }

    $errors = $this->validator->validate($hallDto);

    if (count($errors) > 0) {
      $errorsList = ValidationErrorDto::listOf($errors);
      return $this->json(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    if ($hallDto->name !== null) {
      $hall->name = $hallDto->name;
    }

    if ($hallDto->capacity !== null) {
      $hall->capacity = $hallDto->capacity;
    }

    $this->hallRepository->save($hall);

    return $this->json(
      ['data' => $hall],
      context: ['groups' => ['hall', 'hall_department', 'department']]
    );
  }

  #[Route('/{id}', name: 'read', methods: ['GET'])]
  public function read(int $id): JsonResponse {
    $hall = $this->hallRepository->find($id);

    if ($hall === null) {
      return $this->json(['error' => 'Hall not found'], Response::HTTP_NOT_FOUND);
    }

    return $this->json(
      ['data' => $hall],
      context:  ['groups' => ['hall', 'hall_department', 'department']]
    );
  }

  #[Route('', name: 'read_many', methods: ['GET'])]
  public function readMany(): JsonResponse {
    $halls = $this->hallRepository->findAll();

    return $this->json(
      ['data' => $halls],
      context: ['groups' => ['hall', 'hall_department', 'department']]
    );
  }
}
