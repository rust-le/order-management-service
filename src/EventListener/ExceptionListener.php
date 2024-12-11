<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $response = $this->createResponse($exception);
        $event->setResponse($response);
    }

    private function createResponse(Throwable $exception): JsonResponse
    {
        if ($exception instanceof HttpExceptionInterface) {
            return new JsonResponse([
                'error' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }

        return new JsonResponse([
            'error' => 'Internal Server Error',
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
