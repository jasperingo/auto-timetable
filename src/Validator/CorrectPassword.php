<?php
namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class CorrectPassword extends Constraint {
  public string $message = 'This password is incorrect';
}
