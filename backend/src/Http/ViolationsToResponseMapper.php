<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationsToResponseMapper
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function createResponse(string $message, ConstraintViolationListInterface $violations)
    {
        $violations = array_map(function (ConstraintViolation $v) {
            return [
                'propertyPath' => $v->getPropertyPath(),
                'message' => $v->getMessage()
            ];
        }, iterator_to_array($violations));

        return new JsonResponse(
            $this->serializer->serialize(compact('message', 'violations'), 'json'),
            400,
            [],
            true
        );
    }
}
