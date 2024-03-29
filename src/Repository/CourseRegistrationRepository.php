<?php
namespace App\Repository;

use App\Entity\Course;
use App\Entity\CourseRegistration;
use App\Entity\Student;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class CourseRegistrationRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, CourseRegistration::class);
  }

  public function save(CourseRegistration $courseRegistration) {
    $this->getEntityManager()->persist($courseRegistration);
    $this->getEntityManager()->flush();
  }

  public function delete(CourseRegistration $courseRegistration) {
    $this->getEntityManager()->remove($courseRegistration);
    $this->getEntityManager()->flush();
  }

  public function existsByCourseIdAndStudentIdAndSession(
    int $courseId,
    int $studentId,
    int $session,
  ): bool {
    $courseRegistration = $this->findOneBy([
      'course' => $courseId,
      'student' => $studentId,
      'session' => $session
    ]);
    return $courseRegistration !== null;
  }

  public function findAllBySessionAndCourseId(
    $session,
    $courseId
  ) {
    return $this->getEntityManager()->createQueryBuilder()
      ->select('cr')
      ->from(CourseRegistration::class, 'cr')
      ->Where('cr.session = ?1')
      ->andWhere('cr.course = ?2')
      ->setParameter(1, $session)
      ->setParameter(2, $courseId)
      ->getQuery()
      ->getResult();
  }

  public function findAllBySessionAndSemester(
    $session,
    $semester
  ) {
    return $this->getEntityManager()->createQueryBuilder()
      ->select('cr')
      ->from(CourseRegistration::class, 'cr')
      ->join('cr.course', 'c')
      ->Where('cr.session = ?1')
      ->andWhere('c.semester = ?2')
      ->setParameter(1, $session)
      ->setParameter(2, $semester)
      ->getQuery()
      ->getResult();
  }

  public function findAllByStudentIdAndSessionAndSemester(
    $studentId,
    $session,
    $semester
  ) {
    return $this->getEntityManager()->createQueryBuilder()
      ->select('cr')
      ->from(CourseRegistration::class, 'cr')
      ->join('cr.course', 'c')
      ->join('cr.student', 's')
      ->where('s.id = ?1')
      ->andWhere('cr.session = ?2')
      ->andWhere('c.semester = ?3')
      ->setParameter(1, $studentId)
      ->setParameter(2, $session)
      ->setParameter(3, $semester)
      ->getQuery()
      ->getResult();
  }
}
