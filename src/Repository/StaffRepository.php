<?php
namespace App\Repository;

use App\Entity\Staff;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class StaffRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Staff::class);
  }

  public function save(Staff $staff) {
    $this->getEntityManager()->persist($staff);
    $this->getEntityManager()->flush();
  }

  public function existsByStaffNumber(string $staffNumber): bool {
    $staff = $this->findOneBy(['staffNumber' => $staffNumber]);
    return $staff !== null;
  }
}
