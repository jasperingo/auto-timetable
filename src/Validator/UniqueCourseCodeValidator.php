<?php
namespace App\Validator;

use App\Repository\CourseRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueCourseCodeValidator extends ConstraintValidator {
  public function __construct(private readonly CourseRepository $courseRepository) {}

  public function validate(mixed $value, Constraint $constraint) {
    if (!$constraint instanceof UniqueCourseCode) {
      throw new UnexpectedTypeException($constraint, UniqueCourseCode::class);
    }

    if ($this->courseRepository->existsByCode($value)) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }
}
