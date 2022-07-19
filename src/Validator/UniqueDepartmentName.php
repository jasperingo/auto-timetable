<?php
namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class UniqueDepartmentName extends Constraint {
  public string $message = 'This department name already exists';
}
