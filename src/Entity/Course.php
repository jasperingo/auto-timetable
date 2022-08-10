<?php
namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(CourseRepository::class), Table('courses')]
class Course {
  #[
    Id,
    Column('id', 'integer'),
    GeneratedValue
  ]
  #[Groups(['course'])]
  public int $id;

  #[Column(type: 'string')]
  #[Groups(['course'])]
  public string $title;

  #[Column(type: 'string')]
  #[Groups(['course'])]
  public string $code;

  #[Column(type: 'string', enumType: Semester::class)]
  #[Groups(['course'])]
  public Semester $semester;

  #[
    JoinColumn('departmentId'),
    ManyToOne(Department::class, fetch: 'EAGER', inversedBy: 'courses'),
  ]
  #[Groups(['course_department'])]
  public Department $department;
}
