<?php

namespace App\EventListener;

use App\Exception\InvalidRequestDataException;
use App\Exception\InvalidResponseDataException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof InvalidRequestDataException || $exception instanceof InvalidResponseDataException) {
            $response = new JsonResponse([
                'error' => $exception->getMessage(),
            ], $exception->getStatusCode());

            $event->setResponse($response);
        }
    }
}
