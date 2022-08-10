<?php
namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class UniqueCourseTitle extends Constraint {
  public string $message = 'This course title already exists';
}
