<?php
namespace App\Repository;

use App\Entity\Examination;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ExaminationRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Examination::class);
  }

  public function save(Examination $examination) {
    $this->getEntityManager()->persist($examination);
    $this->getEntityManager()->flush();
  }
}
