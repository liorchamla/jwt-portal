<?php

namespace App\Http\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class HttpExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'handleHttpException'
        ];
    }

    public function handleHttpException(ExceptionEvent $event)
    {
        $ex = $event->getThrowable();

        if (!$ex instanceof HttpException) {
            return;
        }

        $event->setResponse(new JsonResponse([
            'error' => $ex->getMessage()
        ], $ex->getStatusCode()));
    }
}
