<?php
namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\PersistentCollection;

#[Entity(DepartmentRepository::class), Table('departments')]
class Department {
  #[
    Id,
    Column('id', 'integer'),
    GeneratedValue
  ]
  public int $id;

  #[Column(type: 'string')]
  public string $name;

  #[Column(type: 'string')]
  public string $code;

  #[OneToMany('department', Staff::class)]
  public PersistentCollection | array $staffs;

  #[OneToMany('department', Hall::class)]
  public PersistentCollection | array $halls;
}
