<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class ExistingDepartmentId extends Constraint {
  public string $message = 'This department do not exist';
}
