<?php
namespace App\Repository;

use App\Entity\ExaminationHall;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ExaminationHallRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, ExaminationHall::class);
  }

  public function save(ExaminationHall $examinationHall) {
    $this->getEntityManager()->persist($examinationHall);
    $this->getEntityManager()->flush();
  }
}
