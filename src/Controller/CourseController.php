<?php
namespace App\Controller;

use Exception;
use App\Entity\Course;
use App\Security\JwtAuth;
use App\Security\VoterAction;
use App\Dto\UpdateCourseDto;
use App\Dto\CreateCourseDto;
use App\Dto\ValidationErrorDto;
use App\Repository\CourseRepository;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/courses', name: 'course_')]
class CourseController extends AbstractController {
  public function __construct(
    private readonly ValidatorInterface $validator,
    private readonly SerializerInterface $serializer,
    private readonly CourseRepository $courseRepository,
    private readonly DepartmentRepository $departmentRepository,
  ) {}

  #[Route('', name: 'create', methods: ['POST']), JwtAuth]
  public function create(Request $request): JsonResponse {
    $course = new Course;

    $this->denyAccessUnlessGranted(VoterAction::CREATE, $course);

    try {
      $courseDto = $this->serializer->deserialize(
        $request->getContent(),
        CreateCourseDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $courseDto = new CreateCourseDto;
    }

    $errors = $this->validator->validate($courseDto);

    if (count($errors) > 0) {
      $errorsList = ValidationErrorDto::listOf($errors);
      return $this->json(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    $course->title = $courseDto->title;
    $course->code = $courseDto->code;
    $course->level = $courseDto->level;
    $course->semester = $courseDto->semester;
    $course->department = $this->departmentRepository->find($courseDto->departmentId);

    $this->courseRepository->save($course);

    return $this->json(
      ['data' => $course],
      Response::HTTP_CREATED,
      context: ['groups' => ['course', 'course_department', 'department']]
    );
  }

  #[
    Route('/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['PUT']),
    JwtAuth
  ]
  public function update(Request $request, int $id): JsonResponse {
    $course = $this->courseRepository->find($id);

    if ($course === null) {
      return $this->json(['error' => 'Course not found'], Response::HTTP_NOT_FOUND);
    }

    $this->denyAccessUnlessGranted(VoterAction::UPDATE, $course);

    try {
      $courseDto = $this->serializer->deserialize(
        $request->getContent(),
        UpdateCourseDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $courseDto = new UpdateCourseDto;
    }

    $errors = $this->validator->validate($courseDto);

    if (count($errors) > 0) {
      $errorsList = ValidationErrorDto::listOf($errors);
      return $this->json(['errors' => $errorsList], Response::HTTP_BAD_REQUEST);
    }

    if ($courseDto->title !== null) {
      $course->title = $courseDto->title;
    }

    if ($courseDto->code !== null) {
      $course->code = $courseDto->code;
    }

    $this->courseRepository->save($course);

    return $this->json(
      ['data' => $course],
      context:  ['groups' => ['course', 'course_department', 'department']]
    );
  }

  #[Route('/{id}', name: 'read', methods: ['GET'])]
  public function read(int $id): JsonResponse {
    $course = $this->courseRepository->find($id);

    if ($course === null) {
      return $this->json(['error' => 'Course not found'], Response::HTTP_NOT_FOUND);
    }

    return $this->json(
      ['data' => $course],
      context:  ['groups' => ['course', 'course_department', 'department']]
    );
  }
}
