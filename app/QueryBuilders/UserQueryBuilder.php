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
    /**
     * Filtro por usuários ativos
     */
    public function active(): self
    {
        return $this->where('active', true);
    }

    /**
     * Filtro por e-mail
     */
    public function whereEmail(string $email): self
    {
        return $this->where('email', $email);
    }

    /**
     * Busca por nome ou e-mail
     */
    public function search(string $term): self
    {
        return $this->where(function ($query) use ($term) {
            $query
                ->where('name', 'like', "%$term%")
                ->orWhere('email', 'like', "%$term%");
        });
    }

    /**
     * Filtro por data de criação
     */
    public function createdAfter(string $date): self
    {
        return $this->where('created_at', '>=', $date);
    }

    /**
     * Aplica filtros dinâmicos vindos da query string.
     * Exemplo: filter(['isActive' => true, 'name' => 'walter'])
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

    // Métodos semânticos para uso direto
    public function isActive(): self
    {
        return $this->where('active', true);
    }

    public function hasEmail(string $email): self
    {
        return $this->where('email', $email);
    }
}
