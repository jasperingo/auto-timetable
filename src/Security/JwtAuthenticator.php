<?php
namespace App\Security;

use function strlen;
use function strtr;
use function substr;
use Exception;
use ReflectionMethod;
use ReflectionException;
use App\Entity\Staff;
use App\Entity\Student;
use App\Repository\StaffRepository;
use App\Repository\StudentRepository;
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

  private bool $optional = false;

  public function __construct(
    private readonly JwtService $jwtService,
    private readonly StaffRepository $staffRepository,
    private readonly StudentRepository $studentRepository,
  ) {}

  public function supports(Request $request): ?bool {
    try {
      $controller = new ReflectionMethod($request->attributes->get('_controller'));

      if (count($controller->getAttributes(JwtOptionalAuth::class)) > 0) {
        $this->optional = true;
        return true;
      }

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

    return new SelfValidatingPassport(new UserBadge($payload->sub, function ($identifier) use ($payload) {
      if ($payload->subType === Staff::class) {
        return $this->staffRepository->find($identifier);
      }

      if ($payload->subType === Student::class) {
        return $this->studentRepository->find($identifier);
      }

      return null;
    }));
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response {
    return null;
  }

  public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response {
    if ($this->optional) {
      return null;
    }

    $data = [
      'error' => strtr($exception->getMessageKey(), $exception->getMessageData())
    ];
    
    return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
  }
}
