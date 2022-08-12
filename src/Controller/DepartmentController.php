<?php
namespace App\Controller;

use Doctrine\Common\Collections\Criteria;
use function strtoupper;
use Exception;
use App\Entity\Department;
use App\Security\VoterAction;
use App\Security\JwtAuth;
use App\Dto\UpdateDepartmentDto;
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
      return $this->json(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    $department->name = $departmentDto->name;
    $department->code = strtoupper($departmentDto->code);

    $this->departmentRepository->save($department);

    return $this->json(
      ['data' => $department],
      Response::HTTP_CREATED,
      context: ['groups' => 'department'],
    );
  }

  #[Route('/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['PUT']), JwtAuth]
  public function update(Request $request, int $id): JsonResponse {
    $department = $this->departmentRepository->find($id);

    if ($department === null) {
      return $this->json(['error' => 'Department not found'], Response::HTTP_NOT_FOUND);
    }

    $this->denyAccessUnlessGranted(VoterAction::UPDATE, $department);

    try {
      $departmentDto = $this->serializer->deserialize(
        $request->getContent(),
        UpdateDepartmentDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $departmentDto = new UpdateDepartmentDto;
    }

    $errors = $this->validator->validate($departmentDto);

    if (count($errors) > 0) {
      $errorsList = ValidationErrorDto::listOf($errors);
      return $this->json(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    if ($departmentDto->name !== null) {
      $department->name = $departmentDto->name;
    }

    if ($departmentDto->code !== null) {
      $department->code = strtoupper($departmentDto->code);
    }

    $this->departmentRepository->save($department);

    return $this->json(
      ['data' => $department],
      context: ['groups' => 'department']
    );
  }

  #[Route('', name: 'read_many', methods: ['GET'])]
  public function readMany(): JsonResponse {
    $departments = $this->departmentRepository->findAll();

    return $this->json(
      ['data' => $departments],
      context: ['groups' => 'department']
    );
  }

  #[Route('/{id}/halls', name: 'read_many_halls', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function readManyHalls(int $id): JsonResponse {
    $department = $this->departmentRepository->find($id);

    if ($department === null) {
      return $this->json(['error' => 'Department not found'], Response::HTTP_NOT_FOUND);
    }

    return $this->json(
      ['data' => $department->halls],
      context: ['groups' => ['hall']]
    );
  }

  #[Route('/{id}/courses', name: 'read_many_courses', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function readManyCourses(Request $request, int $id): JsonResponse {
    $department = $this->departmentRepository->find($id);

    if ($department === null) {
      return $this->json(['error' => 'Department not found'], Response::HTTP_NOT_FOUND);
    }

    $criteria = Criteria::create()->where(
      Criteria::expr()->eq("semester", $request->query->get('semester'))
    );

    $courses = $department->courses->matching($criteria);

    return $this->json(
      ['data' => $courses],
      context: ['groups' => ['course']]
    );
  }
}
