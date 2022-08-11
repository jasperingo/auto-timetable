<?php
namespace App\Security;

use function count;
use function strtr;
use Exception;
use App\Dto\StudentLoginDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StudentPasswordAuthenticator extends AbstractAuthenticator {
  public function __construct(
    private readonly ValidatorInterface $validator,
    private readonly SerializerInterface $serializer,
  ) {}

  public function supports(Request $request): ?bool {
    return true;
  }

  public function authenticate(Request $request): Passport {
   try {
     $loginDto = $this->serializer->deserialize(
       $request->getContent(),
       StudentLoginDto::class,
       JsonEncoder::FORMAT,
     );
   } catch (Exception) {
     $loginDto = new StudentLoginDto;
   }

    $errors = $this->validator->validate($loginDto);

    if (count($errors) > 0) {
      throw new CustomUserMessageAuthenticationException('Invalid credentials.');
    }

    return new Passport(
      new UserBadge($loginDto->matriculationNumber),
      new PasswordCredentials($loginDto->password)
    );
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response {
    return null;
  }

  public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response {
    $data = [
      'error' => strtr($exception->getMessageKey(), $exception->getMessageData())
    ];

    return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
  }
}