<?php
namespace App\Repository;

use App\Entity\Student;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class StudentRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Student::class);
  }

  public function save(Student $staff) {
    $this->getEntityManager()->persist($staff);
    $this->getEntityManager()->flush();
  }

  public function existsByMatriculationNumber(string $matriculationNumber): bool {
    $student = $this->findOneBy(['matriculationNumber' => $matriculationNumber]);
    return $student !== null;
  }
}