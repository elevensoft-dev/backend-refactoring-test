<?php

namespace App\Swagger\User\V1\GetAllUsersPaginatedControllerDocs;

/**
 * @OA\Schema(
 *     schema="AllUsersPaginatedSuccessResponse",
 *     type="object",
 *     title="All Users Paginnated Success Response",
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
 *         example="Users retrieved successfully."
 *     ),
 *     @OA\Property(
 *         property="pagination",
 *         @OA\Property(ref="#/components/schemas/UserPaginationResource")
 *     )
 * )
 */
class SuccessResponseSchema
{
}

