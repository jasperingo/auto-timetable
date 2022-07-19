<?php
namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class UniqueDepartmentCode extends Constraint {
  public string $message = 'This department code already exists';
}
