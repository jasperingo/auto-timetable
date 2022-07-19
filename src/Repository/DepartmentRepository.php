<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DepartmentRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Department::class);
  }

  public function save(Department $department) {
    $this->getEntityManager()->persist($department);
    $this->getEntityManager()->flush();
  }

  public function existsById(int $id): bool {
    $department = $this->find($id);
    return $department !== null;
  }

  public function existsByName(string $name): bool {
    $department = $this->findOneBy(['name' => $name]);
    return $department !== null;
  }

  public function existsByCode(string $code): bool {
    $department = $this->findOneBy(['code' => $code]);
    return $department !== null;
  }
}
