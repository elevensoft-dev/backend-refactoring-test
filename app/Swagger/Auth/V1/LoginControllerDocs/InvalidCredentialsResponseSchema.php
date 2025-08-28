<?php

namespace App\Swagger\Auth\V1\LoginControllerDocs;

/**
 * @OA\Schema(
 *     schema="InvalidLoginCredentialsResponse",
 *     type="object",
 *     title="Invalid Login Credentials Response",
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
 *             example="Invalid credentials."
 *         )
 *     )
 * )
 */
class InvalidCredentialsResponseSchema
{
}

