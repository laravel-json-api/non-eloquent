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

use LaravelJsonApi\Contracts\Store\Repository;

trait HasModelOrResourceId
{

    /**
     * @var object|null
     */
    protected ?object $model = null;

    /**
     * @var string|null
     */
    protected ?string $resourceId = null;

    /**
     * @var Repository|null
     */
    private ?Repository $repository = null;

    /**
     * Inject the repository.
     *
     * @param Repository $repository
     * @return $this
     */
    public function withRepository(Repository $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Set the model or resource id.
     *
     * @param $modelOrResourceId
     * @return $this
     */
    public function withModelOrResourceId($modelOrResourceId): self
    {
        if (is_string($modelOrResourceId)) {
            $this->model = null;
            $this->resourceId = $modelOrResourceId;
            return $this;
        }

        if (is_object($modelOrResourceId)) {
            $this->model = $modelOrResourceId;
            $this->resourceId = null;
            return $this;
        }

        throw new \InvalidArgumentException('Expecting a string or object.');
    }

    /**
     * Resolve the model.
     *
     * @return object|null
     */
    protected function model(): ?object
    {
        if ($this->model) {
            return $this->model;
        }

        if ($this->resourceId && $this->repository) {
            return $this->repository->find($this->resourceId);
        }

        throw new \RuntimeException('Unable to resolve model: missing resource id and/or repository.');
    }

    /**
     * Resolve the model or fail if it does not exist.
     *
     * @return object
     */
    protected function modelOrFail(): object
    {
        if ($this->model) {
            return $this->model;
        }

        if ($this->resourceId && $this->repository) {
            return $this->repository->findOrFail($this->resourceId);
        }

        throw new \RuntimeException('Unable to resolve model: missing resource id and/or repository.');
    }

    /**
     * Is there a model?
     *
     * @return bool
     */
    protected function hasModel(): bool
    {
        return is_object($this->model) || is_string($this->resourceId);
    }
}
