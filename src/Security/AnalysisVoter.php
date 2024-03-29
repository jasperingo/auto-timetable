<?php
namespace App\Security;

use App\Entity\Staff;
use App\Entity\Analysis;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AnalysisVoter extends Voter {
  protected function supports(string $attribute, mixed $subject): bool {
    return $subject instanceof Analysis;
  }

  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
    $user = $token->getUser();

    if ($user instanceof Staff) {
      return true;
    }

    return false;
  }
}
