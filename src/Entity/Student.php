<?php
namespace App\Entity;

use DateTime;
use App\Repository\StudentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[Entity(StudentRepository::class), Table('students')]
class Student implements UserInterface, PasswordAuthenticatedUserInterface {
  #[
    Id,
		Column('id', 'integer'),
		GeneratedValue,
    Groups(['student'])
	]
	public int $id;

	#[
    Column(type: 'string', nullable: false),
    Groups(['student'])
  ]
	public string $firstName;

	#[
    Column(type: 'string', nullable: false),
    Groups(['student'])
  ]
	public string $lastName;

	#[
    Column(type: 'string', unique: true, nullable: false),
    Groups(['student'])
  ]
	public string $matriculationNumber;

	#[
    Column(type: 'string', nullable: false),
    Groups(['student']),
    Ignore
  ]
	public string $password;

  #[
    Column(type: Types::INTEGER, nullable: false),
    Groups(['student'])
  ]
  public int $joinedAt;

	#[
    Column(type: 'datetime', nullable: false),
    Groups(['student'])
  ]
	public DateTime $createdAt;

  #[
    JoinColumn('departmentId'),
    ManyToOne(Department::class, fetch: 'EAGER', inversedBy: 'staffs'),
    Groups(['student_department'])
  ]
  public Department $department;

  public function getPassword(): ?string {
    return $this->password;
  }

  public function getRoles(): array {
    return [];
  }

  public function eraseCredentials() {
    // TODO: Implement eraseCredentials() method.
  }

  public function getUserIdentifier(): string {
    return $this->matriculationNumber;
  }
}
