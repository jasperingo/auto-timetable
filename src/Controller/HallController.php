<?php
namespace App\Controller;

use Exception;
use App\Entity\Hall;
use App\Repository\DepartmentRepository;
use App\Security\VoterAction;
use App\Dto\CreateHallDto;
use App\Dto\ValidationErrorDto;
use App\Repository\HallRepository;
use App\Security\JwtAuth;
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
      return new JsonResponse(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    $hall->name = $hallDto->name;
    $hall->capacity = $hallDto->capacity;

    if ($hallDto->departmentId !== null) {
      $hall->department = $this->departmentRepository->find($hallDto->departmentId);
    }

    $this->hallRepository->save($hall);

    $hall->department->halls = [];

    return $this->json(['data' => $hall], Response::HTTP_CREATED);
  }
}
