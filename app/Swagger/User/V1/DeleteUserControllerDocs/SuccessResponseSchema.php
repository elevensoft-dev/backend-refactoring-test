<?php

namespace App\Swagger\User\V1\DeleteUserControllerDocs;

/**
 * @OA\Schema(
 *     schema="DeleteUserSuccessResponse",
 *     type="object",
 *     title="Delete User Success Response",
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
 *         example="Users deleted successfully."
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

