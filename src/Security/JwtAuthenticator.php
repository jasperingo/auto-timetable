<?php
namespace App\Security;

use function strlen;
use function strtr;
use function substr;
use Exception;
use ReflectionMethod;
use ReflectionException;
use App\Service\JwtService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class JwtAuthenticator extends AbstractAuthenticator {
  public function __construct(private readonly JwtService $jwtService) {}

  public function supports(Request $request): ?bool {
    try {
      $controller = new ReflectionMethod($request->attributes->get('_controller'));
      return count($controller->getAttributes(JwtAuth::class)) > 0;
    } catch (ReflectionException) {
      throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function authenticate(Request $request): Passport {
    $authHeader = $request->headers->get('Authorization');

    if (null === $authHeader) {
      throw new CustomUserMessageAuthenticationException("No JWT token provided");
    }

    $jwtToken = substr($authHeader, strlen('Bearer '));

    if (empty($jwtToken)) {
      throw new CustomUserMessageAuthenticationException("No JWT token provided");
    }

    try {
      $payload = $this->jwtService->unSign($jwtToken);
    } catch (Exception) {
      throw new CustomUserMessageAuthenticationException("Invalid JWT token provided");
    }

    return new SelfValidatingPassport(new UserBadge($payload->sub));
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
