<?php
namespace App\Validator;

use App\Repository\DepartmentRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueDepartmentCodeValidator extends ConstraintValidator {
  public function __construct(private readonly DepartmentRepository $departmentRepository) {}

  public function validate(mixed $value, Constraint $constraint) {
    if (!$constraint instanceof UniqueDepartmentCode) {
      throw new UnexpectedTypeException($constraint, UniqueDepartmentCode::class);
    }

    if ($this->departmentRepository->existsByCode($value)) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }
}