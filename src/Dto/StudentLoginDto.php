<?php
namespace App\Dto;

use Symfony\Component\Validator\Constraints;

class StudentLoginDto {
  #[Constraints\NotBlank]
  public ?string $matriculationNumber = null;

  #[Constraints\NotBlank]
  public ?string $password = null;
}
