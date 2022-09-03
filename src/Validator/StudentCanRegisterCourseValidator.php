<?php
namespace App\Validator;

use App\Repository\CourseRegistrationRepository;
use function date;
use App\Repository\CourseRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class StudentCanRegisterCourseValidator extends ConstraintValidator {
  public function __construct(
    private readonly Security $security,
    private readonly CourseRepository $courseRepository,
    private readonly CourseRegistrationRepository $courseRegistrationRepository,
  ) {}

  public function validate(mixed $value, Constraint $constraint) {
    if (!$constraint instanceof StudentCanRegisterCourse) {
      throw new UnexpectedTypeException($constraint, StudentCanRegisterCourse::class);
    }

    $year = (int) date('Y');

    $user = (object) $this->security->getUser();

    $userLevel = ($year - $user->joinedAt) + 1;

    $course = (object) $this->courseRepository->find($value);

    if (
      $course->level > $userLevel ||
      $this->courseRegistrationRepository->existsByCourseIdAndStudentIdAndSession($value, $user->id, $year)
    ) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }
}
