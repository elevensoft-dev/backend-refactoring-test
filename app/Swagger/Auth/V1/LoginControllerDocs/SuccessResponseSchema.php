<?php

namespace App\Swagger\Auth\V1\LoginControllerDocs;

/**
 * @OA\Schema(
 *     schema="UserLoginSuccessResponse",
 *     type="object",
 *     title="User Login Success Response",
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
 *         example="User logged in successfully"
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/LoginResource")
 *     )
 * )
 */
class SuccessResponseSchema
{
}

