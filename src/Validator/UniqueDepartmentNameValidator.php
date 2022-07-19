<?php

namespace App\Validator;

use App\Repository\DepartmentRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueDepartmentNameValidator extends ConstraintValidator {
  public function __construct(private readonly DepartmentRepository $departmentRepository) {}

  public function validate(mixed $value, Constraint $constraint) {
    if (!$constraint instanceof UniqueDepartmentName) {
      throw new UnexpectedTypeException($constraint, UniqueDepartmentName::class);
    }

    if ($this->departmentRepository->existsByName($value)) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }
}