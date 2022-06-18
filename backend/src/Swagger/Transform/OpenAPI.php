<?php

namespace App\Swagger\Transform;

use App\Entity\Application;
use cebe\openapi\spec\Components;
use cebe\openapi\spec\OpenApi as SpecOpenApi;
use cebe\openapi\spec\Operation;
use cebe\openapi\spec\Parameter;
use cebe\openapi\spec\PathItem;
use cebe\openapi\spec\Response;
use cebe\openapi\spec\Schema;
use cebe\openapi\spec\SecurityScheme;
use cebe\openapi\spec\Server;
use cebe\openapi\spec\Tag;
use cebe\openapi\Writer;

class OpenAPI
{
    private SpecOpenApi $openApi;

    private function initializeOpenApiSpecification(Application $application, string $baseUrl)
    {
        return new SpecOpenApi([
            'openapi' => '3.0.2',
            'info' => [
                'title' => $application->getTitle(),
                'description' => $application->getDescription()
            ],
            'tags' => [
                new Tag([
                    'name' => 'Account management',
                    'description' => 'Create an account or login !'
                ]),
                new Tag([
                    'name' => 'Operations',
                    'description' => 'All available operations'
                ]),
            ],
            'paths' => [],
            'servers' => [
                new Server([
                    'url' => $baseUrl
                ])
            ],
            'components' => new Components([
                'securitySchemes' => [
                    'JWT' => new SecurityScheme([
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT'
                    ])
                ],
                'schemas' => [
                    'UserObject' => new Schema([
                        'type' => "object",
                        'properties' => [
                            "email" => [
                                "type" => "string",
                                "example" => "jane@doe.com"
                            ],
                            "password" => [
                                "type" => "string",
                                "example" => "y0uR0ck!"
                            ]
                        ]
                    ])
                ]
            ])
        ]);
    }

    public function transformToJson(Application $application, string $url)
    {
        $baseUrl = $url .  '/a/' . $application->getId() . '/u/';
        $accountsUrl = $url .  '/a/' . $application->getId();

        $this->openApi = $this->initializeOpenApiSpecification($application, $baseUrl);

        $registerOperation = $this->generateOperation("Account management", "Create a new account and benefit of authentication functionnalities", "Create a new account", [201 => "Account created successfuly", 400 => "Validation of sent data failed"], "/components/schemas/UserObject", $accountsUrl);

        $loginOperation = $this->generateOperation("Account management", "Authenticate an account and retrieve a JSON Web Token", "Authenticate an account", [200 => "Authenticated successfuly", 400 => "Validation of sent data failed", 401 => "Bad credentials"], "/components/schemas/UserObject", $accountsUrl);

        $this->openApi->paths['/register'] = new PathItem([
            'post' => $registerOperation
        ]);

        $this->openApi->paths['/login'] = new PathItem([
            'post' => $loginOperation
        ]);

        $paths = [];

        foreach ($application->getRoutes() as $route) {
            $availableResponses = [
                200 => "OK",
                404 => "Resource not found"
            ];

            if ($route->isProtected()) {
                $availableResponses[401] = "JWT Not found";
            }

            $details = $this->generateOperation(
                "Operations",
                $route->getDescription() . ($route->isProtected() ? " (protected by authentication)" : ""),
                substr($route->getDescription(), 0, 40) . " ...",
                $availableResponses
            );

            if ($route->isProtected()) {
                $details->security = [
                    new \cebe\openapi\spec\SecurityRequirement([
                        "JWT" => []
                    ])
                ];
            }

            $parameters = [];

            foreach ($route->getClientPatternParameters() as $param) {
                $parameters[] = new Parameter([
                    'name' => $param,
                    'in' => 'path',
                    'required' => true
                ]);
            }

            $details->parameters = $parameters;

            if (empty($paths[$route->getClientPattern()])) {
                $paths[$route->getClientPattern()] = [];
            }

            $paths[$route->getClientPattern()][strtolower($route->getMethod())] = $details;
        }

        foreach ($paths as $path => $operations) {
            $pathItem = new PathItem([]);
            foreach ($operations as $method => $operation) {
                $pathItem->$method = $operation;
            }

            $this->openApi->paths[$path] = $pathItem;
        }



        return Writer::writeToYaml($this->openApi);
    }

    private function generateOperation(
        string $tagName,
        string $description,
        string $sumarry,
        array $responses = [],
        string $schemaRef = null,
        string $specificUrl = null
    ) {
        $operation = new Operation([
            'tags' => [$tagName],
            'description' => $description,
            'summary' => $sumarry,
        ]);

        $operationResponses = [];

        foreach ($responses as $statusCode => $description) {
            $operationResponses[$statusCode] = new Response([
                "description" => $description
            ]);
        }

        $operation->responses = $operationResponses;

        if ($specificUrl) {
            $operation->servers = [
                new Server([
                    'url' => $specificUrl,
                ])
            ];
        }

        if ($schemaRef) {
            $operation->requestBody = [
                "content" => [
                    "application/json" => [
                        "schema" => [
                            '$ref' => "#$schemaRef"
                        ]
                    ],
                ]
            ];
        }

        return $operation;
    }
}
