<?php
namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class StaffHasDepartmentId extends Constraint {
  public string $message = 'You cannot add a resource to this department';

  public function __construct(
    public readonly bool $allowNull = false,
    mixed $options = null,
    array $groups = null,
    mixed $payload = null
  ) {
    parent::__construct($options, $groups, $payload);
  }
}
