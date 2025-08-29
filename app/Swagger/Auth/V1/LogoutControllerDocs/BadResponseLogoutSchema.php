<?php

namespace App\Swagger\Auth\V1\LogoutControllerDocs;

/**
 * @OA\Schema(
 *     schema="BadResponseLogoutResponse",
 *     type="object",
 *     title="Bad Response Logout Response",
 *     @OA\Property(
 *         property="success",
 *         type="boolean",
 *         example=false
 *     ),
 *     @OA\Property(
 *         property="code",
 *         type="integer",
 *         example=400
 *     ),
 *     @OA\Property(
 *         property="error",
 *         type="object",
 *         @OA\Property(property="message", type="string", example="Unxpected error during logout.")
 *     )
 * )
 */
class BadResponseLogoutSchema
{
}
