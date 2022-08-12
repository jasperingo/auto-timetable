<?php
namespace App\Dto;

use App\Entity\Semester;
use App\Validator\ExistingDepartmentId;
use App\Validator\StaffHasDepartmentId;
use App\Validator\UniqueCourseCode;
use App\Validator\UniqueCourseTitle;
use Symfony\Component\Validator\Constraints;

class CreateCourseDto {
  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new UniqueCourseTitle,
  ])]
  public ?string $title = null;

  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new Constraints\Length(6),
    new Constraints\Regex('/^[A-Z]{3}[0-9]{3}$/'),
    new UniqueCourseCode,
  ])]
  public ?string $code = null;

  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new Constraints\Type('integer'),
    new Constraints\GreaterThanOrEqual(1),
  ])]
  public ?int $level = null;

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
    new Constraints\Type('integer'),
    new ExistingDepartmentId,
    new StaffHasDepartmentId,
  ])]
  public ?int $departmentId = null;
}
