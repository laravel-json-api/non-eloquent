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

namespace LaravelJsonApi\NonEloquent\Tests\Integration;

use App\Entities\SiteStorage;
use App\JsonApi\Sites\SiteSchema;
use LaravelJsonApi\Contracts\Schema\Container as SchemaContainerContract;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Schema\Container as SchemaContainer;
use LaravelJsonApi\Contracts\Server\Server;
use LaravelJsonApi\Core\Store\Store;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(SiteStorage::class, fn() => new SiteStorage(
            require __DIR__ . '/../../sites.php'
        ));

        $this->app->singleton(
            SchemaContainerContract::class,
            fn() => new SchemaContainer($this->app, $this->app->make(Server::class), [
                SiteSchema::class,
            ]),
        );

        $this->app->singleton(Server::class, function () {
            $server = $this->createMock(Server::class);
            $server->method('schemas')->willReturnCallback(fn() => $this->schemas());
            return $server;
        });

        $this->app->singleton(StoreContract::class, fn() => new Store($this->schemas()));
    }

    /**
     * @return SchemaContainerContract
     */
    protected function schemas(): SchemaContainerContract
    {
        return $this->app->make(SchemaContainerContract::class);
    }

    /**
     * @return StoreContract
     */
    protected function store(): StoreContract
    {
        return $this->app->make(Store::class);
    }

    /**
     * @return SiteStorage
     */
    protected function sites(): SiteStorage
    {
        return $this->app->make(SiteStorage::class);
    }
}
