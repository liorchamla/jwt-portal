<?php

namespace App\Http\Listener;

use App\Http\Exception\ConstraintsViolationsException;
use App\Http\ViolationsToResponseMapper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ValidationsExceptionSubscriber implements EventSubscriberInterface
{

    public function __construct(private ViolationsToResponseMapper $mapper)
    {
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::EXCEPTION => 'handleValidationException'];
    }

    public function handleValidationException(ExceptionEvent $event)
    {
        $ex = $event->getThrowable();

        if (!$ex instanceof ConstraintsViolationsException) {
            return;
        }

        $event->setResponse(
            $this->mapper->createResponse("Validation errors", $ex->violations)
        );
    }
}
