<?php
/*
 * Copyright 2021 Cloud Creativity Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace LaravelJsonApi\NonEloquent;

use LaravelJsonApi\Contracts\Store\QueriesOne;
use LaravelJsonApi\Contracts\Store\QueriesToMany;
use LaravelJsonApi\Contracts\Store\QueriesToOne;
use LaravelJsonApi\Contracts\Store\QueryManyBuilder;
use LaravelJsonApi\Contracts\Store\QueryOneBuilder;
use LaravelJsonApi\Contracts\Store\Repository;
use RuntimeException;
use function is_object;

abstract class AbstractRepository implements Repository, QueriesOne, QueriesToOne, QueriesToMany
{

    use Concerns\ServerAware;
    use Concerns\SchemaAware;

    /**
     * Create a new repository.
     *
     * @param mixed ...$arguments
     * @return static
     */
    public static function make(...$arguments): self
    {
        if (empty($arguments)) {
            return app(static::class);
        }

        return new static(...$arguments);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $resourceId): bool
    {
        return is_object($this->find($resourceId));
    }

    /**
     * @inheritDoc
     */
    public function findMany(array $resourceIds): iterable
    {
        return collect($resourceIds)
            ->map(fn ($resourceId) => $this->find($resourceId))
            ->filter()
            ->values();
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(string $resourceId): object
    {
        if ($model = $this->find($resourceId)) {
            return $model;
        }

        throw new RuntimeException("Resource {$resourceId} does not exist.");
    }

    /**
     * @inheritDoc
     */
    public function queryOne($modelOrResourceId): QueryOneBuilder
    {
        return Defaults\QueryOne::make($this)
            ->withModelOrResourceId($modelOrResourceId);
    }

    /**
     * @inheritDoc
     */
    public function queryToOne($modelOrResourceId, string $fieldName): QueryOneBuilder
    {
        return Defaults\QueryToOne::make($this->server(), $this)
            ->withModelOrResourceId($modelOrResourceId)
            ->withFieldName($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function queryToMany($modelOrResourceId, string $fieldName): QueryManyBuilder
    {
        return Defaults\QueryToMany::make($this->server(), $this)
            ->withModelOrResourceId($modelOrResourceId)
            ->withFieldName($fieldName);
    }

}
