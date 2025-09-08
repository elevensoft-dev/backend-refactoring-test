<?php

declare(strict_types=1);

namespace App\Models;

use App\QueryBuilders\UserQueryBuilder;
use Hash;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="example@elevensoft.dev"),
 *     @OA\Property(property="email_verified_at", type="datetime", example="2021-01-01 00:00:00"),
 *     @OA\Property(property="password", type="string", example="password"),
 *     @OA\Property(property="remember_token", type="string", example="token"),
 *     @OA\Property(property="created_at", type="datetime", example="2021-01-01 00:00:00"),
 *     @OA\Property(property="updated_at", type="datetime", example="2021-01-01 00:00:00"),
 *     @OA\Property(property="deleted_at", type="datetime", example="2021-01-01 00:00:00", nullable=true),
 * )
 */
class User extends Authenticatable
{
    use CanResetPassword, HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }

    /**
     * @return Attribute<string, string>
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Hash::make($value),
        );
    }
}
