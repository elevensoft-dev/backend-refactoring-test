<?php

namespace App\Swagger\User\V1\UpdateUserControllerDocs;

/**
 * @OA\Schema(
 *     schema="UpdateUserUnprocessableEntityResponse",
 *     type="object",
 *     title="Update User Unprocessale Response",
 *     @OA\Property(
 *         property="success",
 *         type="boolean",
 *         example=false
 *     ),
 *     @OA\Property(
 *         property="code",
 *         type="integer",
 *         example=422
 *     ),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         @OA\Property(
 *             property="name",
 *             type="array",
 *             @OA\Items(type="string", example="The name field is required.")
 *         )
 *     )
 * )
 */
class UnprocessableResponseSchema
{
}
