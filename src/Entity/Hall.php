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

#[Entity(HallRepository::class), Table('halls')]
class Hall {
  #[
    Id,
    Column('id', 'integer'),
    GeneratedValue
  ]
  public int $id;

  #[Column(type: 'string')]
  public string $name;

  #[Column(type: 'integer')]
  public int $capacity;

  #[
    JoinColumn('departmentId'),
    ManyToOne(Department::class, fetch: 'EAGER', inversedBy: 'halls'),
  ]
  public Department $department;
}
