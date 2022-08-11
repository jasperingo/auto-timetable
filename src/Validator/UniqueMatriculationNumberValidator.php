<?php
namespace App\Validator;

use App\Repository\StudentRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueMatriculationNumberValidator extends ConstraintValidator {
  public function __construct(private readonly StudentRepository $studentRepository) {}

  public function validate(mixed $value, Constraint $constraint) {
    if (!$constraint instanceof UniqueMatriculationNumber) {
      throw new UnexpectedTypeException($constraint, UniqueMatriculationNumber::class);
    }

    if ($this->studentRepository->existsByMatriculationNumber($value)) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }
}
