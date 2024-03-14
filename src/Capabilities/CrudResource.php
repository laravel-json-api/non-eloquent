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

use LaravelJsonApi\Contracts\Store\QueryOneBuilder;
use LaravelJsonApi\Contracts\Store\ResourceBuilder;
use LaravelJsonApi\NonEloquent\Concerns\HasModelOrResourceId;
use LogicException;
use function method_exists;
use function sprintf;

class CrudResource extends Capability implements QueryOneBuilder, ResourceBuilder
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

        throw new LogicException('Expecting delete method to exist on CRUD resource capability.');
    }

}
