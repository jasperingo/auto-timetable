<?php
namespace App\Validator;

use App\Repository\DepartmentRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ExistingDepartmentIdValidator extends ConstraintValidator {
  public function __construct(private readonly DepartmentRepository $departmentRepository) {}

  public function validate(mixed $value, Constraint $constraint) {
    if (!$constraint instanceof ExistingDepartmentId) {
      throw new UnexpectedTypeException($constraint, ExistingDepartmentId::class);
    }

    if (
      (!$constraint->allowNull && $value === null) ||
      ($value !== null && !$this->departmentRepository->existsById($value))
    ) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }
}
