<?php
namespace App\Repository;

use App\Entity\Hall;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class HallRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Hall::class);
  }

  public function save(Hall $hall) {
    $this->getEntityManager()->persist($hall);
    $this->getEntityManager()->flush();
  }

  public function existsById(int $id): bool {
    $hall = $this->find($id);
    return $hall !== null;
  }

  public function existsByName(string $name): bool {
    $hall = $this->findOneBy(['name' => $name]);
    return $hall !== null;
  }
}
