<?php
namespace App\Security;

use App\Entity\Staff;
use App\Entity\Student;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class StudentVoter extends Voter {
  protected function supports(string $attribute, mixed $subject): bool {
    return $subject instanceof Student;
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
