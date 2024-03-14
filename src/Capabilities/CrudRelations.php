<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace LaravelJsonApi\NonEloquent\Capabilities;

use LaravelJsonApi\Contracts\Store\QueryManyBuilder;
use LaravelJsonApi\Contracts\Store\QueryOneBuilder;
use LaravelJsonApi\Contracts\Store\ToManyBuilder;
use LaravelJsonApi\Contracts\Store\ToOneBuilder;
use LaravelJsonApi\Core\Support\Str;
use LaravelJsonApi\NonEloquent\Concerns\HasModelResourceIdAndFieldName;
use RuntimeException;
use function sprintf;

class CrudRelations extends Capability implements
    QueryOneBuilder,
    QueryManyBuilder,
    ToOneBuilder,
    ToManyBuilder
{

    use HasModelResourceIdAndFieldName;

    /**
     * @inheritDoc
     */
    public function filter(?array $filters): CrudRelations
    {
        $this->queryParameters->setFilters($filters);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function sort($fields): CrudRelations
    {
        $this->queryParameters->setSortFields($fields);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function first(): ?object
    {
        $method = 'get' . Str::classify($this->fieldName);

        if (method_exists($this, $method)) {
            return $this->{$method}($this->modelOrFail());
        }

        $value = $this->value();

        if (is_object($value) || is_null($value)) {
            return $value;
        }

        throw new RuntimeException(sprintf(
            'Expecting resource to return an object or null for relation %s.',
            $this->fieldName,
        ));
    }


    /**
     * @inheritDoc
     */
    public function get(): iterable
    {
        $method = 'get' . Str::classify($this->fieldName);

        if (method_exists($this, $method)) {
            return $this->{$method}($this->modelOrFail());
        }

        $value = $this->value();

        if (is_iterable($value)) {
            return $value;
        }

        throw new RuntimeException(sprintf(
            'Expecting resource to return an iterable value for relation %s.',
            $this->fieldName,
        ));
    }

    /**
     * @inheritDoc
     */
    public function associate(?array $identifier): ?object
    {
        $method = 'set' . Str::classify($this->fieldName);

        if (method_exists($this, $method)) {
            $related = $this->toOne($identifier);
            $this->{$method}($this->modelOrFail(), $related);
            return $related;
        }

        throw new RuntimeException(sprintf(
            'Expecting capability %s to have method "%s" to modify relation "%s".',
            static::class,
            $method,
            $this->fieldName,
        ));
    }

    /**
     * @inheritDoc
     */
    public function sync(array $identifiers): iterable
    {
        $method = 'set' . Str::classify($this->fieldName);

        if (method_exists($this, $method)) {
            $related = $this->toMany($identifiers);
            $this->{$method}($this->modelOrFail(), $related);
            return $related;
        }

        throw new RuntimeException(sprintf(
            'Expecting capability %s to have method "%s" to modify relation "%s".',
            static::class,
            $method,
            $this->fieldName,
        ));
    }

    /**
     * @inheritDoc
     */
    public function attach(array $identifiers): iterable
    {
        $method = 'attach' . Str::classify($this->fieldName);

        if (method_exists($this, $method)) {
            $related = $this->toMany($identifiers);
            $this->{$method}($this->modelOrFail(), $related);
            return $related;
        }

        throw new RuntimeException(sprintf(
            'Expecting capability %s to have method "%s" to attach resources to relation "%s".',
            static::class,
            $method,
            $this->fieldName,
        ));
    }

    /**
     * @inheritDoc
     */
    public function detach(array $identifiers): iterable
    {
        $method = 'detach' . Str::classify($this->fieldName);

        if (method_exists($this, $method)) {
            $related = $this->toMany($identifiers);
            $this->{$method}($this->modelOrFail(), $related);
            return $related;
        }

        throw new RuntimeException(sprintf(
            'Expecting capability %s to have method "%s" to detach resources from relation "%s".',
            static::class,
            $method,
            $this->fieldName,
        ));
    }

}
