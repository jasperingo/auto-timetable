<?php
namespace App\Entity;

use DateTime;
use App\Repository\TimetableRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(TimetableRepository::class), Table('timetables')]
class Timetable {
  #[
    Id,
		Column('id', Types::INTEGER),
		GeneratedValue,
    Groups(['timetable'])
	]
	public int $id;

  #[
    Column(type: Types::INTEGER, nullable: false),
    Groups(['timetable'])
  ]
  public int $session;

  #[
    Column(type: Types::STRING, enumType: Semester::class),
    Groups(['timetable'])
  ]
  public Semester $semester;

  #[
    Column(type: Types::DATETIME_MUTABLE, nullable: false),
    Groups(['timetable'])
  ]
	public DateTime $createdAt;

  #[
    OneToMany('timetable', Examination::class),
    Groups(['timetable_examinations'])
  ]
  public PersistentCollection $examinations;
}
