<?php
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\StaffRepository;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[Entity(StaffRepository::class), Table('staffs')]
class Staff implements UserInterface, PasswordAuthenticatedUserInterface {
  #[
		Id, 
		Column('id', 'integer'),
		GeneratedValue
	]
	public int $id;

  #[Column(type: 'string')]
	public ?string $title;

	#[Column(type: 'string', nullable: false)]
	public string $firstName;

	#[Column(type: 'string', nullable: false)]
	public string $lastName;
	
	#[Column(type: 'string', unique: true, nullable: false)]
	public string $staffNumber;
	
	#[Column(type: 'string', nullable: false)]
	public string $password;

  #[Column(type: 'string', enumType: StaffRole::class)]
  public StaffRole $role;
	
	#[Column(type: 'datetime', nullable: false)]
	public DateTime $createdAt;

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
