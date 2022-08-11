<?php
namespace App\Dto;

use App\Validator\UniqueCourseCode;
use App\Validator\UniqueCourseTitle;
use Symfony\Component\Validator\Constraints;

class UpdateCourseDto {
  #[Constraints\AtLeastOneOf([
    new Constraints\IsNull,
    new Constraints\Sequentially([
      new Constraints\NotBlank,
      new UniqueCourseTitle,
    ]),
  ])]
  public ?string $title = null;

  #[Constraints\AtLeastOneOf([
    new Constraints\IsNull,
    new Constraints\Sequentially([
      new Constraints\NotBlank,
      new Constraints\Length(6),
      new Constraints\Regex('/^[A-Z]{3}[0-9]{3}$/'),
      new UniqueCourseCode,
    ])
  ])]
  public ?string $code = null;
}
