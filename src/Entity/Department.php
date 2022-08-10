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
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(DepartmentRepository::class), Table('departments')]
class Department {
  #[
    Id,
    Column('id', 'integer'),
    GeneratedValue
  ]
  #[Groups(['department'])]
  public int $id;

  #[Column(type: 'string')]
  #[Groups(['department'])]
  public string $name;

  #[Column(type: 'string')]
  #[Groups(['department'])]
  public string $code;

  #[OneToMany('department', Staff::class)]
  #[Groups(['department_staffs'])]
  public PersistentCollection | array $staffs;

  #[OneToMany('department', Hall::class)]
  #[Groups(['department_halls'])]
  public PersistentCollection | array $halls;
}
