<?php

namespace App\Swagger\Auth\V1\LogoutControllerDocs;

/**
 * @OA\Schema(
 *     schema="UserLogoutSuccessResponse",
 *     type="object",
 *     title="User Logout Success Response",
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
 *         example="User logged out successfully"
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/LogoutResource")
 *     )
 * )
 */
class SuccessResponseSchema
{
}

