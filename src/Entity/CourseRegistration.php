<?php
namespace App\Entity;

use App\Repository\CourseRegistrationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups;

#[
  Entity(CourseRegistrationRepository::class),
  Table('course_registrations')
]
class CourseRegistration {
  #[
    Id,
    GeneratedValue,
    Column('id', Types::INTEGER),
    Groups(['course_registration'])
  ]
  public int $id;

  #[
    Column(type: Types::INTEGER, nullable: false),
    Groups(['course_registration'])
  ]
  public int $session;

  #[
    JoinColumn('courseId'),
    ManyToOne(Course::class, fetch: 'EAGER', inversedBy: 'courseRegistrations'),
    Groups(['course_registration_course'])
  ]
  public Course $course;

  #[
    JoinColumn('studentId'),
    ManyToOne(Student::class, fetch: 'EAGER', inversedBy: 'courseRegistrations'),
    Groups(['course_registration_student'])
  ]
  public Student $student;
}
