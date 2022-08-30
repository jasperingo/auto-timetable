<?php
namespace App\Dto;

use App\Validator\ExistingDepartmentId;
use App\Validator\StaffHasDepartmentId;
use App\Validator\UniqueHallName;
use Symfony\Component\Validator\Constraints;

class CreateHallDto {
  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new UniqueHallName,
  ])]
  public ?string $name = null;

  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new Constraints\Type('integer'),
    new Constraints\GreaterThan(0)
  ])]
  public ?int $capacity = null;

  #[Constraints\Sequentially([
    new Constraints\NotBlank(allowNull: true),
    new Constraints\Type('integer'),
    new ExistingDepartmentId(true),
    new StaffHasDepartmentId(true),
  ])]
  public ?int $departmentId = null;
}
