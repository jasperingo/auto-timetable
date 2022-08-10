<?php
namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class ExistingDepartmentId extends Constraint {
  public string $message = 'This department do not exist';

  public function __construct(
    public readonly bool $allowNull = false,
    mixed $options = null,
    array $groups = null,
    mixed $payload = null
  ) {
    parent::__construct($options, $groups, $payload);
  }
}
