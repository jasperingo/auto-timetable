<?php
namespace App\Security;

use App\Entity\Staff;
use App\Entity\StaffRole;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class StaffVoter extends Voter {
  protected function supports(string $attribute, mixed $subject): bool {
    return $subject instanceof Staff;
  }

  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
    $user = $token->getUser();

    if (
      $attribute === VoterAction::CREATE &&
      $user instanceof Staff &&
      $user->role !== StaffRole::Invigilator
    ) {
      return true;
    }

    if (
      $attribute === VoterAction::UPDATE &&
      $user instanceof Staff &&
      $subject->id === $user->id
    ) {
      return true;
    }

    return false;
  }
}
