<?php
namespace App\Controller;

use function strtoupper;
use Exception;
use App\Entity\Department;
use App\Security\VoterAction;
use App\Security\JwtAuth;
use App\Dto\ValidationErrorDto;
use App\Dto\CreateDepartmentDto;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/departments', name: 'department_')]
class DepartmentController extends AbstractController {
  public function __construct(
    private readonly ValidatorInterface $validator,
    private readonly SerializerInterface $serializer,
    private readonly DepartmentRepository $departmentRepository,
  ) {}

  #[Route('', name: 'create', methods: ['POST']), JwtAuth]
  public function create(Request $request): JsonResponse {
    $department = new Department;

    $this->denyAccessUnlessGranted(VoterAction::CREATE, $department);

    try {
      $departmentDto = $this->serializer->deserialize(
        $request->getContent(),
        CreateDepartmentDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $departmentDto = new CreateDepartmentDto;
    }

    $errors = $this->validator->validate($departmentDto);

    if (count($errors) > 0) {
      $errorsList = ValidationErrorDto::listOf($errors);
      return new JsonResponse(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    $department->name = $departmentDto->name;
    $department->code = strtoupper($departmentDto->code);

    $this->departmentRepository->save($department);

    return $this->json(['data' => $department], Response::HTTP_CREATED);
  }
}
