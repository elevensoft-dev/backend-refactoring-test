<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ModelsFactoryTrait
{
    /**
     * Creates a new instance without store on database.
     */
    private function makeModel(string $modelClass, array $attributes = []): Model
    {
        return $modelClass::factory()->make($attributes);
    }

    /**
     * Creates multiple instances without store on database.
     */
    private function makeManyModels(string $modelClass, int $count = 1, array $attributes = []): Collection
    {
        return $modelClass::factory($count)->make($attributes);
    }

    /**
     * Create a new instance and store on database
     */
    private function createModel(string $modelClass, array $attributes = []): Model
    {
        return $modelClass::factory()->create($attributes);
    }

    /**
     * Creates multiple instances and store on database.
     */
    private function createManyModels(string $modelClass, int $count = 1, array $attributes = []): Collection
    {
        return $modelClass::factory($count)->make($attributes);
    }
}
