<?php
namespace App\Repository;

use App\Entity\ExaminationInvigilator;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ExaminationInvigilatorRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, ExaminationInvigilator::class);
  }

  public function save(ExaminationInvigilator $examinationInvigilator) {
    $this->getEntityManager()->persist($examinationInvigilator);
    $this->getEntityManager()->flush();
  }
}
