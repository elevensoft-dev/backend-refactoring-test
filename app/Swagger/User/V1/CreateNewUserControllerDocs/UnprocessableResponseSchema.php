<?php

namespace App\Swagger\User\V1\CreateNewUserControllerDocs;

/**
 * @OA\Schema(
 *     schema="CreateUserUnprocessableEntityResponse",
 *     type="object",
 *     title="Create New User Unprocessale Response",
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
 *         @OA\AdditionalProperties(
 *             type="array",
 *             @OA\Items(type="string", example="The name field is required."),
 *             @OA\Items(type="string", example="The email field is required."),
 *             @OA\Items(type="string", example="The password field is required."),
 *             @OA\Items(type="string", example="The email has already been taken."),
 *             @OA\Items(type="string", example="The email field must be a valid email address."),
 *             @OA\Items(type="string", example="The password field confirmation does not match."),
 *             @OA\Items(type="string", example="The password field must be at least 6 characters.")
 *         )
 *     )
 * )
 */
class UnprocessableResponseSchema
{
}
