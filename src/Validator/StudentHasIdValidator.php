<?php
namespace App\Validator;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class StudentHasIdValidator extends ConstraintValidator {
  public function __construct(private readonly Security $security) {}

  public function validate(mixed $value, Constraint $constraint) {
    if (!$constraint instanceof StudentHasId) {
      throw new UnexpectedTypeException($constraint, StudentHasId::class);
    }

    $user = (object) $this->security->getUser();

    if ($user->id !== $value) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }
}
