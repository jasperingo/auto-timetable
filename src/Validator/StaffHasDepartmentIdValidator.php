<?php
namespace App\Validator;

use App\Entity\Staff;
use App\Entity\StaffRole;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class StaffHasDepartmentIdValidator extends ConstraintValidator {
  public function __construct(private readonly Security $security) {}

  public function validate(mixed $value, Constraint $constraint) {
    if (!$constraint instanceof StaffHasDepartmentId) {
      throw new UnexpectedTypeException($constraint, StaffHasDepartmentId::class);
    }

    $user = (object) $this->security->getUser();

    if (
      (!$constraint->allowNull && $value === null) ||
      (
        $value !== null &&
        (
          !($user instanceof Staff) ||
          (
            $user->role !== StaffRole::Admin &&
            $user->department->id !== $value
          )
        )
      )
    ) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }
}
