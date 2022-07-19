<?php
namespace App\Dto;

use App\Entity\StaffRole;
use App\Validator\ExistingDepartmentId;
use App\Validator\PermittedRole;
use App\Validator\UniqueStaffNumber;
use Symfony\Component\Validator\Constraints;

class CreateStaffDto {
  #[Constraints\NotBlank(allowNull: true)]
  public ?string $title = null;

  #[Constraints\NotBlank]
  public ?string $firstName = null;

  #[Constraints\NotBlank]
  public ?string $lastName = null;

  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new UniqueStaffNumber
  ])]
  public ?string $staffNumber = null;

  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new Constraints\Length(min: 6),
  ])]
  public ?string $password = null;

  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new Constraints\Choice(choices: [
      StaffRole::ExamOfficer,
      StaffRole::Invigilator
    ]),
    new PermittedRole,
  ])]
  public ?StaffRole $role = null;

  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new Constraints\Type('integer'),
    new ExistingDepartmentId,
  ])]
  public ?int $departmentId = null;
}
