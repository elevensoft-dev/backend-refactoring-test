<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends Builder<User>
 */
final class UserQueryBuilder extends Builder
{
    public function active(): self
    {
        return $this->where('active', true);
    }

    public function whereEmail(string $email): self
    {
        return $this->where('email', $email);
    }

    public function search(string $term): self
    {
        return $this->where(function ($query) use ($term) {
            $query
                ->where('name', 'like', "%$term%")
                ->orWhere('email', 'like', "%$term%");
        });
    }

    public function createdAfter(string $date): self
    {
        return $this->where('created_at', '>=', $date);
    }

    /**
     * Exemplo: filter(['isActive' => true, 'name' => 'john'])
     */
    public function filter(array $filters = []): self
    {
        foreach ($filters as $key => $value) {
            if (! $value) {
                continue;
            }

            $method = 'filter'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }

        return $this;
    }

    public function filterIsActive($active = true): self
    {
        return $this->where('active', filter_var($active, FILTER_VALIDATE_BOOLEAN));
    }

    public function filterName(string $name): self
    {
        return $this->where('name', 'like', "%$name%");
    }

    public function filterEmail(string $email): self
    {
        return $this->where('email', 'like', "%$email%");
    }

    public function filterSearch(string $term): self
    {
        return $this->where(function ($query) use ($term) {
            $query
                ->where('name', 'like', "%$term%")
                ->orWhere('email', 'like', "%$term%");
        });
    }

    public function isActive(): self
    {
        return $this->where('active', true);
    }

    public function hasEmail(string $email): self
    {
        return $this->where('email', $email);
    }
}
