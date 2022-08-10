<?php
namespace App\Security;

use App\Entity\Course;
use App\Entity\Staff;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CourseVoter extends Voter {
  protected function supports(string $attribute, mixed $subject): bool {
    return $subject instanceof Course;
  }

  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
    $user = $token->getUser();

    if (
      $attribute === VoterAction::CREATE &&
      $user instanceof Staff
    ) {
      return true;
    }

    return false;
  }
}
