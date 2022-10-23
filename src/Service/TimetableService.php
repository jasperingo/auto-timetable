<?php
namespace App\Service;

use App\Entity\Hall;
use App\Entity\Examination;

class TimetableService {
  public function getHallVacancy(Hall $hall, array $examinations): int {
    $occupied = 0;

    foreach($examinations as $examination) {
      $examHalls = $examination->halls;
      foreach($examHalls as $examHall) {
        if ($examHall->hall->id === $hall->id) {
          $occupied += $examHall->capacity;
        }
      }
    }

    return $hall->capacity - $occupied;
  }

  public function getExamCapacityVacancy(Examination $examination): int {
    $occupied = 0;

    foreach($examination->halls as $examHall) {
      $occupied += $examHall->capacity;
    }

    return $examination->numberOfStudents - $occupied;
  }
}