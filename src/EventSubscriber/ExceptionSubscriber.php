<?php
namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionSubscriber implements EventSubscriberInterface {
  public function __construct(private readonly LoggerInterface $logger) {}

  public static function getSubscribedEvents(): array {
    return [
      KernelEvents::EXCEPTION => 'processException',
    ];
  }

  public function processException(ExceptionEvent $event) {
    $exception = $event->getThrowable();

    $this->logger->error($exception);

    $response = new JsonResponse();
    $response->setData([
      'error' => $exception->getMessage(),
      'errorCode' => $exception->getCode(),
    ]);

    if ($exception instanceof HttpExceptionInterface) {
      $response->setStatusCode($exception->getStatusCode());
      // $response->headers->replace($exception->getHeaders());
    } else {
      $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    $event->setResponse($response);
  }
}
