<?php
namespace App\Validator;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CorrectPasswordValidator extends ConstraintValidator {
  public function __construct(
    private readonly Security $security,
    private readonly UserPasswordHasherInterface $passwordHasher
  ) {}

  public function validate(mixed $value, Constraint $constraint) {
    if (!$constraint instanceof CorrectPassword) {
      throw new UnexpectedTypeException($constraint, CorrectPassword::class);
    }

    $user = (object) $this->security->getUser();

    if (!$this->passwordHasher->isPasswordValid($user, $value)) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }
}
