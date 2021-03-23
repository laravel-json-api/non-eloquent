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

use LaravelJsonApi\Contracts\Store\CreatesResources;
use LaravelJsonApi\Contracts\Store\DeletesResources;
use LaravelJsonApi\Contracts\Store\QueryOneBuilder;
use LaravelJsonApi\Contracts\Store\ResourceBuilder;
use LaravelJsonApi\Contracts\Store\UpdatesResources;
use LaravelJsonApi\NonEloquent\Capabilities\Crud;

abstract class CrudRepository extends AbstractRepository implements
    CreatesResources,
    UpdatesResources,
    DeletesResources
{

    /**
     * Get the CRUD capability.
     *
     * @return Crud
     */
    abstract protected function crud(): Crud;

    /**
     * @inheritDoc
     */
    public function create(): ResourceBuilder
    {
        return $this->usingCrud();
    }

    /**
     * @inheritDoc
     */
    public function queryOne($modelOrResourceId): QueryOneBuilder
    {
        return $this
            ->usingCrud()
            ->withModelOrResourceId($modelOrResourceId);
    }

    /**
     * @inheritDoc
     */
    public function update($modelOrResourceId): ResourceBuilder
    {
        return $this
            ->usingCrud()
            ->withModelOrResourceId($modelOrResourceId);
    }

    /**
     * @inheritDoc
     */
    public function delete($modelOrResourceId): void
    {
        $this->usingCrud()
            ->withModelOrResourceId($modelOrResourceId)
            ->destroy();
    }

    /**
     * @return Crud
     */
    private function usingCrud(): Crud
    {
        return $this->crud()
            ->maybeWithServer($this->server)
            ->maybeWithSchema($this->schema)
            ->withRepository($this);
    }

}
