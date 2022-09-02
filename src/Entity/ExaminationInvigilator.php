<?php
namespace App\Entity;

use App\Repository\ExaminationInvigilatorRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Annotation\Groups;

#[
  Entity(ExaminationInvigilatorRepository::class), 
  Table('examination_invigilators')
]
class ExaminationInvigilator {
  #[
    Id,
		Column('id', Types::INTEGER),
		GeneratedValue,
    Groups(['examination_invigilator'])
	]
	public int $id;

  #[
    JoinColumn('examinationId'),
    ManyToOne(Examination::class, fetch: 'EAGER', inversedBy: 'invigilators'),
    Groups(['examination_invigilator_examination'])
  ]
  public Examination $examination;

  #[
    JoinColumn('staffId'),
    ManyToOne(Staff::class, fetch: 'EAGER', inversedBy: 'examinationInvigilators'),
    Groups(['examination_invigilator_staff'])
  ]
  public Staff $staff;
}
