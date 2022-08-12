<?php
namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class StudentCanRegisterCourse extends Constraint {
  public string $message = 'You cannot register for this course';
}
