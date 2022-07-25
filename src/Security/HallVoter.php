<?php
namespace App\Security;

use App\Entity\Hall;
use App\Entity\Staff;
use App\Entity\StaffRole;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class HallVoter extends Voter {
  protected function supports(string $attribute, mixed $subject): bool {
    return $subject instanceof Hall;
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
      (
        $user->role === StaffRole::Admin ||
        (
          $user->role === StaffRole::ExamOfficer &&
          $user->department->id === $subject->department->id
        )
      )
    ) {
      return true;
    }

    return false;
  }
}
