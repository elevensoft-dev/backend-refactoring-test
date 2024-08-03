<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Create a new class instance.
     */
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
