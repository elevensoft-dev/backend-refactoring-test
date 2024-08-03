<?php

namespace App\Api\Swagger\User;

use Illuminate\Http\Response;
use OpenApi\Annotations\JsonContent;
use OpenApi\Attributes as OA;

trait UserSwagger
{
    #[
        OA\Get(
            path: '/users',
            operationId: 'getUsersList',
            summary: 'Get List of users',
            description: 'Returns list of users',
            tags: ['Users'],
            security: [
                [
                    'bearerAuth' => []
                ]
            ],
            requestBody: new OA\RequestBody(
                content: new OA\MediaType(
                    mediaType: 'application/json',

                )
            ),
            responses: [

                new OA\Response(response: Response::HTTP_OK,  description: 'Successful operation'),
                new OA\Response(response: Response::HTTP_UNAUTHORIZED,  description: 'Unauthenticated'),
                new OA\Response(response: Response::HTTP_FORBIDDEN,  description: 'Forbidden'),
            ]
        )
    ]
    private function all(): void
    {
    }

    #[
        OA\Get(
            path: '/users/{id}',
            operationId: 'showUser',
            summary: 'Show a specific user',
            description: 'Returns a specific user',
            tags: ['Users'],
            security: [
                [
                    'bearerAuth' => []
                ]
            ],
            parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, description: 'User ID')],
            responses: [
                new OA\Response(response: Response::HTTP_OK,  description: 'Successful operation'),
                new OA\Response(response: Response::HTTP_UNAUTHORIZED,  description: 'Unauthenticated'),
                new OA\Response(response: Response::HTTP_FORBIDDEN,  description: 'Forbidden'),
            ]
        )
    ]
    private function swagger_show(): void
    {
    }
}
