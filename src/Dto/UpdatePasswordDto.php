<?php
namespace App\Dto;

use App\Validator\CorrectPassword;
use Symfony\Component\Validator\Constraints;

class UpdatePasswordDto {
  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new Constraints\Length(min: 6),
  ])]
  public ?string $password = null;

  #[Constraints\Sequentially([
    new Constraints\NotBlank,
    new Constraints\Length(min: 6),
    new CorrectPassword,
  ])]
  public ?string $currentPassword = null;
}
