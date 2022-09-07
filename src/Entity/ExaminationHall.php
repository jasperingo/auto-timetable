<?php
namespace App\Entity;

use App\Repository\ExaminationHallRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Annotation\Groups;

#[
  Entity(ExaminationHallRepository::class), 
  Table('examination_halls')
]
class ExaminationHall {
  #[
    Id,
		Column('id', Types::INTEGER),
		GeneratedValue,
    Groups(['examination_hall'])
	]
	public int $id;

  #[
    Column(type: Types::INTEGER),
    Groups(['examination_hall'])
  ]
  public int $capacity;

  #[
    JoinColumn('examinationId'),
    ManyToOne(Examination::class, fetch: 'EAGER', inversedBy: 'halls'),
    Groups(['examination_hall_examination'])
  ]
  public Examination $examination;

  #[
    JoinColumn('hallId'),
    ManyToOne(Hall::class, fetch: 'EAGER', inversedBy: 'examinationHalls'),
    Groups(['examination_hall_hall'])
  ]
  public Hall $hall;
}
