<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class PermittedRole extends Constraint {
  public string $message = 'You cannot add a staff with this role';
}
