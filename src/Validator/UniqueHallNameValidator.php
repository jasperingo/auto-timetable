<?php
namespace App\Validator;

use App\Repository\HallRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueHallNameValidator extends ConstraintValidator {
  public function __construct(private readonly HallRepository $hallRepository) {}

  public function validate(mixed $value, Constraint $constraint) {
    if (!$constraint instanceof UniqueHallName) {
      throw new UnexpectedTypeException($constraint, UniqueHallName::class);
    }

    if ($this->hallRepository->existsByName($value)) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }
}
