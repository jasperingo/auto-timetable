<?php
namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class PermittedRole extends Constraint {
  public string $message = 'You cannot add a staff with this role';
}
