<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="UsersNotFoundResponse",
 *     type="object",
 *     title="Users Not Found Response",
 *     @OA\Property(
 *         property="success",
 *         type="boolean",
 *         example=false
 *     ),
 *     @OA\Property(
 *         property="code",
 *         type="integer",
 *         example=404
 *     ),
 *     @OA\Property(
 *         property="error",
 *         type="object",
 *         @OA\Property(property="message", type="string", example="No users found in the database.")
 *     )
 * )
 */
class UsersNotFoundResponseSchema
{
}
