<?php
namespace App\Dto;

use App\Validator\ExistingDepartmentId;
use App\Validator\StaffHasDepartmentId;
use App\Validator\UniqueMatriculationNumber;
use Symfony\Component\Validator\Constraints;

class CreateStudentDto {
  #[Constraints\NotBlank]
  public ?string $firstName = null;

  #[Constraints\NotBlank]
  public ?string $lastName = null;

  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new UniqueMatriculationNumber,
    new Constraints\Length(min: 11),
  ])]
  public ?string $matriculationNumber = null;

  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new Constraints\Length(min: 6),
  ])]
  public ?string $password = null;

  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new Constraints\Type('integer'),
    new ExistingDepartmentId,
    new StaffHasDepartmentId,
  ])]
  public ?int $departmentId = null;
}
