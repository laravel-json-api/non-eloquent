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

use LaravelJsonApi\Contracts\Server\Server;
use LaravelJsonApi\Contracts\Store\Repository;
use LaravelJsonApi\NonEloquent\Capabilities\QueryToMany as BaseCapability;

final class QueryToMany extends BaseCapability
{

    /**
     * @var Repository
     */
    private Repository $repository;

    /**
     * @var Server
     */
    private Server $server;

    /**
     * QueryToMany constructor.
     *
     * @param Repository $repository
     * @param Server $server
     */
    public function __construct(Repository $repository, Server $server)
    {
        $this->repository = $repository;
        $this->server = $server;
    }

    /**
     * @inheritDoc
     */
    public function get(): iterable
    {
        $resource = $this->server->resources()->create(
            $this->modelOrFail()
        );

        return $resource->relationship($this->fieldName)->data();
    }

}