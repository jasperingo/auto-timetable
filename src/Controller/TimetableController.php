<?php
namespace App\Controller;

use function date;
use DateTime;
use Exception;
use App\Security\JwtAuth;
use App\Entity\Timetable;
use App\Security\VoterAction;
use App\Dto\CreateTimetableDto;
use App\Dto\ValidationErrorDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

#[Route('/timetables', name: 'timetable_')]
class TimetableController extends AbstractController {
  public function __construct(
    private readonly ValidatorInterface $validator,
    private readonly SerializerInterface $serializer,
  ) {}

  #[Route('', name: 'create', methods: ['POST']), JwtAuth]
  public function create(Request $request): JsonResponse {
    $timetable = new Timetable;
  
    $this->denyAccessUnlessGranted(VoterAction::CREATE, $timetable);

    try {
      $timetableDto = $this->serializer->deserialize(
        $request->getContent(),
        CreateTimetableDto::class,
        JsonEncoder::FORMAT,
      );
    } catch (Exception) {
      $timetableDto = new CreateTimetableDto;
    }

    $errors = $this->validator->validate($timetableDto);

    if (count($errors) > 0) {
      return $this->json(
        ['errors' => ValidationErrorDto::listOf($errors)], 
        Response::HTTP_BAD_REQUEST
      );
    }

    $timetable->session = (int) date('Y');
    $timetable->createdAt = new DateTime;
    $timetable->semester = $timetableDto->semester;

    // TODO: COMPUTE TIMETABLE...
    
    return $this->json(['data' => $timetable], Response::HTTP_CREATED);
  }
}
