<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contract\AbstractRepository;

class UserRepository extends AbstractRepository{
    public function __construct() {
        $this->model = new User();
    }
}
