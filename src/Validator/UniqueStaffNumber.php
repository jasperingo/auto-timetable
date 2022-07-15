<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueStaffNumber extends Constraint {
  public string $message = 'This staff number already exists';
}
