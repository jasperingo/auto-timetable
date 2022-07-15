<?php
namespace App\EventSubscriber;

use function sprintf;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionSubscriber implements EventSubscriberInterface {
  public static function getSubscribedEvents() {
    return [
      KernelEvents::EXCEPTION => 'processException',
    ];
  }

  public function processException(ExceptionEvent $event) {
    $exception = $event->getThrowable();

    $message = sprintf(
        'My Error says: %s with code: %s',
        $exception->getMessage(),
        $exception->getCode()
    );

    $response = new JsonResponse();
    $response->setData(['message' => $message]);

    if ($exception instanceof HttpExceptionInterface) {
      $response->setStatusCode($exception->getStatusCode());
      // $response->headers->replace($exception->getHeaders());
    } else {
      $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    $event->setResponse($response);
  }
}
