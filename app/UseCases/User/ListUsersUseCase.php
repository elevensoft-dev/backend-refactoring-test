<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Models\User;
use App\QueryBuilders\UserQueryBuilder;
use App\UseCases\User\DTOs\ListUsersDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListUsersUseCase
{
    public function handle(ListUsersDto $dto): LengthAwarePaginator
    {
        /** @var UserQueryBuilder $query */
        $query = User::query();

        $query->filter($dto->filters);

        return $query->paginate($dto->perPage);
    }
}
