<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DepartmentRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, DepartmentRepository::class);
  }

  public function save(Department $staff) {
    $this->getEntityManager()->persist($staff);
    $this->getEntityManager()->flush();
  }

  public function existsByStaffNumber(string $staffNumber): bool {
    $staff = $this->findOneBy(['staffNumber' => $staffNumber]);
    return $staff !== null;
  }
}