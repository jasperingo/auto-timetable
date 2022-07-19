<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class CorrectPassword extends Constraint {
  public string $message = 'This password is incorrect';
}
