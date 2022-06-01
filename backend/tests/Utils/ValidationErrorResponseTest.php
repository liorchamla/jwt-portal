<?php

namespace App\Test\Utils;

use App\Http\ViolationsToResponse;
use App\Http\ViolationsToResponseMapper;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationErrorResponseTest extends KernelTestCase
{
    /** @test */
    public function it_should_transform_validator_violations_to_json()
    {
        static::bootKernel();

        // Given we have a list of constraints violations :
        $violations = new ConstraintViolationList([
            new ConstraintViolation("MOCK_MESSAGE_1", "", [], "", "MOCK_PROPERTY_PATH_1", null, null),
            new ConstraintViolation("MOCK_MESSAGE_2", "", [], "", "MOCK_PROPERTY_PATH_2", null, null),
        ]);

        // When we give it to our ValidationErrorResponse
        $violationsToResponse = new ViolationsToResponseMapper(self::getContainer()->get(SerializerInterface::class));

        $response = $violationsToResponse->createResponse("MOCK_RESPONSE_MESSAGE", $violations);

        // Then the status of the response should be 400
        static::assertEquals(400, $response->getStatusCode());
        static::assertEquals([
            'message' => "MOCK_RESPONSE_MESSAGE",
            'violations' => [
                [
                    'propertyPath' => "MOCK_PROPERTY_PATH_1",
                    'message' => "MOCK_MESSAGE_1"
                ],
                [
                    'propertyPath' => "MOCK_PROPERTY_PATH_2",
                    'message' => "MOCK_MESSAGE_2"
                ],
            ]
        ], json_decode($response->getContent(), true));
    }
}
