<?php

namespace App\Swagger\User\V1\UpdateUserControllerDocs;

/**
 * @OA\Schema(
 *     schema="UpdateUserSuccessResponse",
 *     type="object",
 *     title="Update User Success Response",
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
 *         example="User updated successfully."
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

