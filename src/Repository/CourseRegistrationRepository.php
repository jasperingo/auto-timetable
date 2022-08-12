<?php
namespace App\Repository;

use App\Entity\CourseRegistration;
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
}
