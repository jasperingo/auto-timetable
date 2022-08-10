<?php
namespace App\Validator;

use App\Repository\CourseRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueCourseTitleValidator extends ConstraintValidator {
  public function __construct(private readonly CourseRepository $courseRepository) {}

  public function validate(mixed $value, Constraint $constraint) {
    if (!$constraint instanceof UniqueCourseTitle) {
      throw new UnexpectedTypeException($constraint, UniqueCourseTitle::class);
    }

    if ($this->courseRepository->existsByTitle($value)) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }
}
