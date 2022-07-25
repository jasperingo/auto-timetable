<?php
namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class UniqueHallName extends Constraint {
  public string $message = 'This hall name already exists';
}