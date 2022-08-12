<?php
namespace App\Security;

use App\Entity\Student;
use App\Entity\CourseRegistration;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CourseRegistrationVoter extends Voter {
  protected function supports(string $attribute, mixed $subject): bool {
    return $subject instanceof CourseRegistration;
  }


  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
    $user = $token->getUser();

    if (
      $attribute === VoterAction::CREATE &&
      $user instanceof Student
    ) {
      return true;
    }

    if (
      $attribute === VoterAction::DELETE &&
      $user instanceof Student &&
      $user->id === $subject->student->id
    ) {
      return true;
    }

    return false;
  }
}
