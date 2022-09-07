<?php
namespace App\Entity;

use App\Repository\HallRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(HallRepository::class), Table('halls')]
class Hall {
  #[
    Id,
    Column('id', 'integer'),
    GeneratedValue,
    Groups(['hall'])
  ]
  public int $id;

  #[
    Column(type: 'string'),
    Groups(['hall'])
  ]
  public string $name;

  #[
    Column(type: 'integer'),
    Groups(['hall'])
  ]
  public int $capacity;

  #[
    JoinColumn('departmentId'),
    ManyToOne(Department::class, fetch: 'EAGER', inversedBy: 'halls'),
    Groups(['hall_department'])
  ]
  public ?Department $department;

  #[
    OneToMany('hall', ExaminationHall::class),
    Groups(['hall_examination_halls'])
  ]
  public PersistentCollection $examinationHalls;
}
