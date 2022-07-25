<?php
namespace App\Dto;

use App\Validator\UniqueHallName;
use Symfony\Component\Validator\Constraints;

class UpdateHallDto {
  #[Constraints\AtLeastOneOf([
    new Constraints\IsNull,
    new Constraints\Sequentially([
      new Constraints\NotBlank,
      new UniqueHallName,
    ])
  ])]
  public ?string $name = null;

  #[Constraints\AtLeastOneOf([
    new Constraints\IsNull,
    new Constraints\Sequentially([
      new Constraints\NotBlank,
      new Constraints\Type('integer'),
      new Constraints\GreaterThan(0)
    ])
  ])]
  public ?int $capacity = null;
}
