<?php

namespace App\Swagger\User\V1\GetAllUsersControllerDocs;

/**
 * @OA\Schema(
 *     schema="AllUsersSuccessResponse",
 *     type="object",
 *     title="All Users Success Response",
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
 *         example="Users retrieved successfully."
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

