<?php
namespace App\Service;

use DateTime;
use DateInterval;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService {
  const ALGO = 'HS256';

  public function sign(int $id, string $userType): string {
    $date = new DateTime();
    $date->add(new DateInterval('P30D'));

    $payload = new JwtPayload($id, $userType, $date->getTimestamp());

    return JWT::encode($payload->toArray(), $_ENV['JWT_KEY'], self::ALGO);
  }

  public function unSign(string $token): JwtPayload {
    $payload = JWT::decode($token, new Key($_ENV['JWT_KEY'], self::ALGO));

    return new JwtPayload(
      $payload->sub,
      $payload->userType,
      $payload->iat,
      $payload->exp,
    );
  }
}
