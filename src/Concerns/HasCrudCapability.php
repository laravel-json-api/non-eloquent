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
