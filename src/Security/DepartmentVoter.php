<?php
namespace App\Security;

use App\Entity\Staff;
use App\Entity\StaffRole;
use App\Entity\Department;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class DepartmentVoter extends Voter {
  protected function supports(string $attribute, mixed $subject): bool {
    return $subject instanceof Department;
  }

  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
    $user = $token->getUser();

    if (
      $attribute === VoterAction::CREATE &&
      $user instanceof Staff &&
      $user->role === StaffRole::Admin
    ) {
      return true;
    }

    return false;
  }
}
