<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace LaravelJsonApi\NonEloquent\Concerns;

use LaravelJsonApi\Contracts\Store\QueryManyBuilder;
use LaravelJsonApi\Contracts\Store\QueryOneBuilder;
use LaravelJsonApi\Contracts\Store\ToManyBuilder;
use LaravelJsonApi\Contracts\Store\ToOneBuilder;
use LaravelJsonApi\NonEloquent\Capabilities\CrudRelations;

trait HasRelationsCapability
{

    /**
     * @return CrudRelations
     */
    abstract protected function relations(): CrudRelations;

    /**
     * @inheritDoc
     */
    public function queryToOne($modelOrResourceId, string $fieldName): QueryOneBuilder
    {
        return $this
            ->usingRelations()
            ->withModelOrResourceId($modelOrResourceId)
            ->withFieldName($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function queryToMany($modelOrResourceId, string $fieldName): QueryManyBuilder
    {
        return $this
            ->usingRelations()
            ->withModelOrResourceId($modelOrResourceId)
            ->withFieldName($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function modifyToOne($modelOrResourceId, string $fieldName): ToOneBuilder
    {
        return $this
            ->usingRelations()
            ->withModelOrResourceId($modelOrResourceId)
            ->withFieldName($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function modifyToMany($modelOrResourceId, string $fieldName): ToManyBuilder
    {
        return $this
            ->usingRelations()
            ->withModelOrResourceId($modelOrResourceId)
            ->withFieldName($fieldName);
    }

    /**
     * @return CrudRelations
     */
    private function usingRelations(): CrudRelations
    {
        return $this
            ->relations()
            ->maybeWithServer($this->server)
            ->maybeWithSchema($this->schema)
            ->withRepository($this);
    }

}
