<?php

namespace App\Repository\User\V1;

use App\Models\User;
use App\Repository\BaseRepository;
use App\Repository\User\V1\Contracts\UserRepositoryInterface;

/**
 * Inherits all crud methods from BaseRepository.
 * Add here other specific methods if needed.
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}
