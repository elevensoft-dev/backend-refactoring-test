<?php

namespace App\Api\Swagger\Auth;

use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

trait AuthSwagger
{
    #[
        OA\Post(
            path: "/auth/login",
            summary: "Login User",
            tags: ["Authenticate"],
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        required: ["email", "password"],
                        properties: [
                            new OA\Property(property: 'email', description: "User email", type: "string"),
                            new OA\Property(property: 'password', description: "User password", type: "string"),
                        ]
                    ),
                    example: [
                        'email' => 'email@email.com',
                        'password' => 'password'
                    ]
                )
            ),

            responses: [
                new OA\Response(
                    response: Response::HTTP_ACCEPTED,
                    description: "Login success.",
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'data', type: 'object', example: [
                                "success" => true,
                                "statusCode" => Response::HTTP_ACCEPTED,
                                "message" => "User has been logged successfully.",
                                "data" => [
                                    "id" => 1,
                                    "name" => "User Teste",
                                    "email" => "email@email.com",
                                    "created_at" => "2024-07-27T15:39:28.000000Z",
                                    "updated_at" => "2024-07-28T21:49:06.000000Z",
                                    "bearer_token" => "17|QJmAJPmZPxxcAFCp8XgfgQf6GzEghBvTEgkgEHrAf7040d89"
                                ]
                            ])
                        ]
                    )
                ),
                new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized", content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object', example: [
                            "meta" => [
                                "code" => Response::HTTP_UNAUTHORIZED,
                                "status" => "fails",
                                "message" => "The provided credentials are incorrect.!"
                            ],
                            "user" => [],
                            "access_token" => [
                                "token" => '',
                                "type" => "Bearer"
                            ]
                        ])
                    ]
                )),
            ]
        )
    ]
    private function swagger_login()
    {
    }
    #[
        OA\Post(
            path: '/auth/logout',
            summary: 'Logout User',
            tags: ['Authenticate'],
            security: [
                [
                    'bearerAuth' => []
                ]
            ],
            responses: [
                new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: 'Unauthenticated', content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object', example: [
                            "message" => "Unauthenticated."
                        ])
                    ]
                )),
                new OA\Response(response: Response::HTTP_ACCEPTED, description: 'Successfully logged out', content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object', example: [
                            'meta' => [
                                'code' => Response::HTTP_ACCEPTED,
                                'status' => 'success',
                                'message' => 'Successfully logged out',
                            ],
                            'data' => [],
                        ])
                    ]
                )),
            ]
        )
    ]
    private function swagger_logout(): void
    {
    }
}
