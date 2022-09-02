<?php
namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(CourseRepository::class), Table('courses')]
class Course {
  #[
    Id,
    Column('id', Types::INTEGER),
    GeneratedValue,
    Groups(['course'])
  ]
  public int $id;

  #[
    Column(type: Types::STRING),
    Groups(['course'])
  ]
  public string $title;

  #[
    Column(type: Types::STRING),
    Groups(['course'])
  ]
  public string $code;

  #[
    Column(type: Types::INTEGER),
    Groups(['course'])
  ]
  public int $level;

  #[
    Column(type: Types::STRING, enumType: Semester::class),
    Groups(['course'])
  ]
  public Semester $semester;

  #[
    JoinColumn('departmentId'),
    ManyToOne(Department::class, fetch: 'EAGER', inversedBy: 'courses'),
    Groups(['course_department'])
  ]
  public Department $department;

  #[
    OneToMany('course', CourseRegistration::class),
    Groups(['course_registrations'])
  ]
  public PersistentCollection | array $courseRegistrations;

  #[
    OneToMany('course', Examination::class),
    Groups(['course_examinations'])
  ]
  public PersistentCollection $examinations;
}
