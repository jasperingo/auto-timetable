<?php
namespace App\Validator;

use App\Entity\StaffRole;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PermittedRoleValidator extends ConstraintValidator {
  public function __construct(private readonly Security $security) {}

  public function validate(mixed $value, Constraint $constraint) {
    if (!$constraint instanceof PermittedRole) {
      throw new UnexpectedTypeException($constraint, PermittedRole::class);
    }

    $user = (object) $this->security->getUser();

    if ($user->role === StaffRole::Admin) {
      return true;
    }

    if (
      $user->role === StaffRole::Invigilator ||
      ($user->role === StaffRole::ExamOfficer && $value !== StaffRole::Invigilator)
    ) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }
}
