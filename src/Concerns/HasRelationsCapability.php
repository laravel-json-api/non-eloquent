<?php
/*
 * Copyright 2023 Cloud Creativity Limited
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
