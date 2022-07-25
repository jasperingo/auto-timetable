<?php
namespace App\Dto;

use App\Validator\UniqueDepartmentCode;
use App\Validator\UniqueDepartmentName;
use Symfony\Component\Validator\Constraints;

class UpdateDepartmentDto {
  #[Constraints\AtLeastOneOf([
    new Constraints\IsNull,
    new Constraints\Sequentially([
      new Constraints\NotBlank,
      new UniqueDepartmentName,
    ])
  ])]
  public ?string $name = null;

  #[Constraints\AtLeastOneOf([
    new Constraints\IsNull,
    new Constraints\Sequentially([
      new Constraints\NotBlank,
      new Constraints\Length(3),
      new Constraints\Regex('/^[A-Z]{3}$/'),
      new UniqueDepartmentCode,
    ])
  ])]
  public ?string $code = null;
}
