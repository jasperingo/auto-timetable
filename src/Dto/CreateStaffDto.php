<?php
namespace App\Dto;

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
}
