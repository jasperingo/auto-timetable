<?php
namespace App\Entity;

use DateTime;
use App\Repository\StaffRepository;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[Entity(StaffRepository::class), Table('staffs')]
class Staff implements UserInterface, PasswordAuthenticatedUserInterface {
  #[
		Id, 
		Column('id', 'integer'),
		GeneratedValue,
    Groups(['staff'])
	]
	public int $id;

  #[
    Column(type: 'string'),
    Groups(['staff'])
  ]
	public ?string $title;

	#[
    Column(type: 'string', nullable: false),
    Groups(['staff'])
  ]
	public string $firstName;

	#[
    Column(type: 'string', nullable: false),
    Groups(['staff'])
  ]
	public string $lastName;
	
	#[
    Column(type: 'string', unique: true, nullable: false),
    Groups(['staff'])
  ]
	public string $staffNumber;
	
	#[
    Column(type: 'string', nullable: false),
    Groups(['staff']),
    Ignore
  ]
	public string $password;

  #[
    Column(type: 'string', enumType: StaffRole::class),
    Groups(['staff'])
  ]
  public StaffRole $role;
	
	#[
    Column(type: 'datetime', nullable: false),
    Groups(['staff'])
  ]
	public DateTime $createdAt;

  #[
    JoinColumn('departmentId'),
    ManyToOne(Department::class, fetch: 'EAGER', inversedBy: 'staffs'),
    Groups(['staff_department'])
  ]
  public Department $department;

  #[
    OneToMany('staff', ExaminationInvigilator::class),
    Groups(['staff_examination_invigilators'])
  ]
  public PersistentCollection $examinationInvigilators;

  public function getPassword(): ?string {
    return $this->password;
  }

  public function getRoles(): array {
    return array_column(StaffRole::cases(), 'value');
  }

  public function eraseCredentials() {
    // TODO: Implement eraseCredentials() method.
  }

  public function getUserIdentifier(): string {
    return $this->staffNumber;
  }
}
