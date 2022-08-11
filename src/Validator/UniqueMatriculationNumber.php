<?php
namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class UniqueMatriculationNumber extends Constraint {
  public string $message = 'This matriculation number already exists';
}
