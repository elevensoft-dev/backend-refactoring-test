<?php

namespace App\Swagger\User\V1\CreateNewUserControllerDocs;

/**
 * @OA\Schema(
 *     schema="CreateNewUserSuccessResponse",
 *     type="object",
 *     title="Create New User Success Response",
 *     @OA\Property(
 *         property="success",
 *         type="boolean",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="code",
 *         type="integer",
 *         example=201
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         example="User created successfully."
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/UserResource")
 *     )
 * )
 */
class SuccessResponseSchema
{
}

