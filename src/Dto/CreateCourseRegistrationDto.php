<?php
namespace App\Dto;

use App\Validator\ExistingCourseId;
use App\Validator\StudentCanRegisterCourse;
use App\Validator\StudentHasId;
use Symfony\Component\Validator\Constraints;

class CreateCourseRegistrationDto {
  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new Constraints\Type('integer'),
    new ExistingCourseId,
    new StudentCanRegisterCourse,
  ])]
  public ?int $courseId = null;

  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new Constraints\Type('integer'),
    new StudentHasId,
  ])]
  public ?int $studentId = null;
}
