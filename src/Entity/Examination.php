<?php
namespace App\Entity;

use App\Repository\ExaminationRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(ExaminationRepository::class), Table('examinations')]
class Examination {
  #[
    Id,
		Column('id', Types::INTEGER),
		GeneratedValue,
    Groups(['examination'])
	]
	public int $id;

  #[
		Column(type: Types::INTEGER, nullable: false),
    Groups(['examination'])
	]
	public int $duration;

  #[
		Column(type: Types::INTEGER, nullable: false),
    Groups(['examination'])
	]
  public int $numberOfStudents;

  #[
    Column(type: Types::DATETIME_MUTABLE, nullable: false),
    Groups(['examination'])
  ]
	public DateTime $startAt;

  #[
    JoinColumn('timetableId'),
    ManyToOne(Timetable::class, fetch: 'EAGER', inversedBy: 'examinations'),
    Groups(['examination_timetable'])
  ]
  public Timetable $timetable;

  #[
    JoinColumn('courseId'),
    ManyToOne(Course::class, fetch: 'EAGER', inversedBy: 'examinations'),
    Groups(['examination_course'])
  ]
  public Course $course;

  #[
    OneToMany('examination', ExaminationHall::class),
    Groups(['examination_halls'])
  ]
  public PersistentCollection $halls;

  #[
    OneToMany('examination', ExaminationInvigilator::class),
    Groups(['examination_invigilators'])
  ]
  public PersistentCollection $invigilators;
}
