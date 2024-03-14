<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace LaravelJsonApi\NonEloquent;

use LaravelJsonApi\Contracts\Store\QueriesOne;
use LaravelJsonApi\Contracts\Store\QueriesToMany;
use LaravelJsonApi\Contracts\Store\QueriesToOne;
use LaravelJsonApi\Contracts\Store\QueryManyBuilder;
use LaravelJsonApi\Contracts\Store\QueryOneBuilder;
use LaravelJsonApi\Contracts\Store\Repository;
use LaravelJsonApi\NonEloquent\Capabilities\CrudRelations;
use LaravelJsonApi\NonEloquent\Capabilities\CrudResource;
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
        return CrudResource::make()
            ->withRepository($this)
            ->withModelOrResourceId($modelOrResourceId);
    }

    /**
     * @inheritDoc
     */
    public function queryToOne($modelOrResourceId, string $fieldName): QueryOneBuilder
    {
        return CrudRelations::make()
            ->withServer($this->server())
            ->withRepository($this)
            ->withModelOrResourceId($modelOrResourceId)
            ->withFieldName($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function queryToMany($modelOrResourceId, string $fieldName): QueryManyBuilder
    {
        return CrudRelations::make()
            ->withServer($this->server())
            ->withRepository($this)
            ->withModelOrResourceId($modelOrResourceId)
            ->withFieldName($fieldName);
    }
}
