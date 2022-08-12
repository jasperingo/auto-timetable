<?php
namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class StudentHasId extends Constraint {
  public string $message = 'You cannot add a resource to this student';
}
