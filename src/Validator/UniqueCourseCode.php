<?php
namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class UniqueCourseCode extends Constraint {
  public string $message = 'This course code already exists';
}
