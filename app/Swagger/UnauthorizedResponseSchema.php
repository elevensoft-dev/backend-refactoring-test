<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="UnauthorizedResponse",
 *     type="object",
 *     title="Unauthorized Response",
 *     @OA\Property(
 *         property="success",
 *         type="boolean",
 *         example=false
 *     ),
 *     @OA\Property(
 *         property="code",
 *         type="integer",
 *         example=401
 *     ),
 *     @OA\Property(
 *         property="error",
 *         type="object",
 *         @OA\Property(
 *             property="message",
 *             type="string",
 *             example="Unauthenticated."
 *         )
 *     )
 * )
 */
class UnauthorizedResponseSchema
{
}

