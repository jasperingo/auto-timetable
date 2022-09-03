<?php
namespace App\Repository;

use App\Entity\Timetable;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class TimetableRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Timetable::class);
  }

  public function save(Timetable $timetable) {
    $this->getEntityManager()->persist($timetable);
    $this->getEntityManager()->flush();
  }
}
