<?php
namespace App\Validator;

use App\Repository\CourseRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ExistingCourseIdValidator extends ConstraintValidator {
  public function __construct(private readonly CourseRepository $courseRepository) {}

  public function validate(mixed $value, Constraint $constraint) {
    if (!$constraint instanceof ExistingCourseId) {
      throw new UnexpectedTypeException($constraint, ExistingCourseId::class);
    }

    if (!$this->courseRepository->existsById($value)) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }
}
