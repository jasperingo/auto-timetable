<?php
namespace App\Repository;

use App\Entity\Timetable;
use Symfony\Bridge\Doctrine\ManagerRegistry;
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
