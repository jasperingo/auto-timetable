<?php
namespace App\Repository;

use App\Entity\Course;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class CourseRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Course::class);
  }

  public function save(Course $course) {
    $this->getEntityManager()->persist($course);
    $this->getEntityManager()->flush();
  }

  public function existsById(int $id): bool {
    $course = $this->find($id);
    return $course !== null;
  }

  public function existsByTitle(string $title): bool {
    $course = $this->findOneBy(['title' => $title]);
    return $course !== null;
  }

  public function existsByCode(string $code): bool {
    $course = $this->findOneBy(['code' => $code]);
    return $course !== null;
  }

  public function findAllByStudentLevelAndDepartmentIdAndLevelAndSemester(
    $studentLevel,
    $level,
    $semester,
    $departmentId
  ) {
    return $this->getEntityManager()->createQueryBuilder()
      ->select('c')
      ->from(Course::class, 'c')
      ->where('c.level <= ?1')
      ->setParameter(1, $studentLevel)
      ->andWhere('c.level = ?2')
        ->setParameter(2, $level)
      ->andWhere('c.semester = ?3')
        ->setParameter(3, $semester)
      ->andWhere('c.department = ?4')
        ->setParameter(4, $departmentId)
      ->getQuery()
      ->getResult();
  }
}
