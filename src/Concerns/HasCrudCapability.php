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

use LaravelJsonApi\Contracts\Store\QueryOneBuilder;
use LaravelJsonApi\Contracts\Store\ResourceBuilder;
use LaravelJsonApi\NonEloquent\Capabilities\CrudResource;

trait HasCrudCapability
{

    /**
     * Get the CRUD resource capability.
     *
     * @return CrudResource
     */
    abstract protected function crud(): CrudResource;

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
     * @return CrudResource
     */
    private function usingCrud(): CrudResource
    {
        return $this->crud()
            ->maybeWithServer($this->server)
            ->maybeWithSchema($this->schema)
            ->withRepository($this);
    }
}
