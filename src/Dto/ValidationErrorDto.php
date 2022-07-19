<?php
namespace App\Dto;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationErrorDto {
  public static function listOf(ConstraintViolationListInterface $errors): array {
    $errorsList = [];
    foreach ($errors as $error) {
      $errorsList[] = [
        'name' => $error->getPropertyPath(),
        'value' => $error->getInvalidValue(),
        'message' => $error->getMessage(),
      ];
    }
    return $errorsList;
  }
}
