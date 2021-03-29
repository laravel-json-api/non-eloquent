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

namespace LaravelJsonApi\NonEloquent\Capabilities;

use LaravelJsonApi\Contracts\Store\QueryOneBuilder;
use LaravelJsonApi\Contracts\Store\ResourceBuilder;
use LaravelJsonApi\NonEloquent\Concerns\HasModelOrResourceId;
use LogicException;
use function method_exists;
use function sprintf;

abstract class CrudResource extends Capability implements QueryOneBuilder, ResourceBuilder
{

    use HasModelOrResourceId;

    /**
     * @inheritDoc
     */
    public function filter(?array $filters): CrudResource
    {
        $this->queryParameters->setFilters($filters);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function first(): ?object
    {
        $model = $this->model();

        if ($model && method_exists($this, 'read')) {
            return $this->read($model);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function store(array $validatedData): object
    {
        $hasModel = $this->hasModel();

        if ($hasModel && method_exists($this, 'update')) {
            $model = $this->modelOrFail();
            return $this->update($model, $validatedData) ?: $model;
        }

        if (!$hasModel && method_exists($this, 'create')) {
            return $this->create($validatedData);
        }

        throw new LogicException(sprintf(
            'Expecting %s method to exist on CRUD resource capability.',
            $hasModel ? 'update' : 'create',
        ));
    }

    /**
     * Destroy the model.
     *
     * @return void
     */
    public function destroy(): void
    {
        $model = $this->modelOrFail();

        if (method_exists($this, 'delete')) {
            $this->delete($model);
            return;
        }

        throw new LogicException('Expecting delete method to exist on CRUD capability.');
    }

}
