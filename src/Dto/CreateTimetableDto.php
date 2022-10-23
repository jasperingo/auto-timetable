<?php
namespace App\Dto;

use App\Entity\Semester;
use Symfony\Component\Validator\Constraints;

class CreateTimetableDto {
  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new Constraints\Choice(choices: [
      Semester::First,
      Semester::Second,
    ]),
  ])]
  public ?Semester $semester = null;

  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new Constraints\Date,
  ])]
  public ?string $startAt = null;
}
