<?php

namespace App\Swagger\User\V1\GetUserByIdControllerDocs;

/**
 * @OA\Schema(
 *     schema="GetUserByIdSuccessResponse",
 *     type="object",
 *     title="Get User By Id Success Response",
 *     @OA\Property(
 *         property="success",
 *         type="boolean",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="code",
 *         type="integer",
 *         example=200
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         example="User retrieved successfully"
 *     ),
 *     @OA\Property(
 *         property="data",
 *         @OA\Schema(ref="#/components/schemas/UserResource"),
 *     )
 * )
 */
class SuccessResponseSchema
{
}

