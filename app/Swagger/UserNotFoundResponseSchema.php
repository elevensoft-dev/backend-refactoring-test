<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="UserNotFoundResponse",
 *     type="object",
 *     title="User Not Found Response",
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
 *         @OA\Property(property="message", type="string", example="User not found.")
 *     )
 * )
 */
class UserNotFoundResponseSchema
{
}
