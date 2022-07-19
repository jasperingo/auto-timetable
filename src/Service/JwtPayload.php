<?php
namespace App\Service;

use function time;

class JwtPayload {
  public function __construct(
    public int $sub,
    public string $subType,
    public int $exp,
    public ?int $iat = null,
  ) {
    $this->iat = time();
  }

  public function toArray(): array {
    return [
      'sub' => $this->sub,
      'subType' => $this->subType,
      'iat' => $this->iat,
      'exp' => $this->exp,
    ];
  }
}
