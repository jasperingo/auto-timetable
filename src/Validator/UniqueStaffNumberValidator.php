<?php

namespace App\Validator;

use App\Repository\StaffRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueStaffNumberValidator extends ConstraintValidator {
  public function __construct(
    private readonly StaffRepository $staffRepository,
  ) {}

  public function validate(mixed $value, Constraint $constraint) {
    if (!$constraint instanceof UniqueStaffNumber) {
      throw new UnexpectedTypeException($constraint, UniqueStaffNumber::class);
    }

    if ($this->staffRepository->existsByStaffNumber($value)) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }
}
