<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * User id
     *
     * @OA\Property(
     *      property="id",
     *      description="User id",
     *      type="integer",
     *      example=1
     * )
     *
     * @var int
     */
    private int $id;

    /**
     * User name
     *
     * @OA\Property(
     *      property="name",
     *      description="User name",
     *      type="string",
     *      example="John Doe"
     * )
     *
     * @var string
     */
    private string $name;

    /**
     * User email
     *
     * @OA\Property(
     *      property="email",
     *      description="User email",
     *      type="string",
     *      example="example@elevensoft.dev"
     * )
     *
     * @var string
     */
    private string $email;

    /**
     * User verified at
     *
     * @OA\Property(
     *      property="email_verified_at",
     *      description="User email verified at",
     *      type="datetime",
     *      example="2021-01-01 00:00:00"
     * )
     *
     * @var Carbon
     */
    private Carbon $email_verified_at;

    /**
     * User password
     *
     * @OA\Property(
     *      property="password",
     *      description="User password",
     *      type="string",
     *      example="password"
     * )
     *
     * @var string
     */
    private string $password;

    /**
     * User remember token
     *
     * @OA\Property(
     *      property="remember_token",
     *      description="User remember token",
     *      type="string",
     *      example="token"
     * )
     *
     * @var string
     */
    private string $remember_token;

    /**
     * User created at
     *
     * @OA\Property(
     *      property="created_at",
     *      description="User created at",
     *      type="datetime",
     *      example="2021-01-01 00:00:00"
     * )
     *
     * @var Carbon
     */
    private Carbon $created_at;

    /**
     * User updated at
     *
     * @OA\Property(
     *      property="updated_at",
     *      description="User updated at",
     *      type="datetime",
     *      example="2021-01-01 00:00:00"
     * )
     *
     * @var Carbon
     */
    private Carbon $updated_at;
}

