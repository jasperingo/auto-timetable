<?php
namespace App\Dto;

use Symfony\Component\Validator\Constraints;

class StaffLoginDto {
  #[Constraints\NotBlank]
  public ?string $staffNumber = null;

  #[Constraints\NotBlank]
  public ?string $password = null;
}
