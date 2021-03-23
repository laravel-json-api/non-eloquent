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

namespace LaravelJsonApi\NonEloquent\Defaults;

use LaravelJsonApi\Contracts\Store\Repository;
use LaravelJsonApi\NonEloquent\Capabilities\QueryOne as BaseCapability;
use RuntimeException;
use function is_object;
use function is_string;

final class QueryOne extends BaseCapability
{

    /**
     * @var Repository
     */
    private Repository $repository;

    /**
     * QueryOne constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function first(): ?object
    {
        if (is_string($this->modelOrResourceId)) {
            return $this->repository->find($this->modelOrResourceId);
        }

        if (is_object($this->modelOrResourceId)) {
            return $this->modelOrResourceId;
        }

        throw new RuntimeException('Expecting model or resource id to be set.');
    }


}
