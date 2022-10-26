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

  public function findAllByExaminationAndCourseRegisteredByStudent(
    int $timetableId,
    int $studentId,
  ) {
    return $this->getEntityManager()->createQueryBuilder()
      ->select('t, e, c')
      ->from(Timetable::class, 't')
      ->join('t.examinations', 'e')
      ->join('e.course', 'c')
      ->join('c.courseRegistrations', 'cr')
      ->where('cr.student = ?1')
      ->andWhere('t.id = ?2')
      ->setParameter(1, $studentId)
      ->setParameter(2, $timetableId)
      ->getQuery()
      ->getSingleResult();
  }

  public function findAllByExaminationAndInvigilator(
    int $timetableId,
    int $staffId,
  ) {
    return $this->getEntityManager()->createQueryBuilder()
      ->select('t, e, c')
      ->from(Timetable::class, 't')
      ->join('t.examinations', 'e')
      ->join('e.course', 'c')
      ->join('e.invigilators', 'i')
      ->where('i.staff = ?1')
      ->andWhere('e.timetable = ?2')
      ->setParameter(1, $staffId)
      ->setParameter(2, $timetableId)
      ->getQuery()
      ->getSingleResult();
  }
}
