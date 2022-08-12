<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class ExistingCourseId extends Constraint {
  public string $message = 'This course do not exist';
}
