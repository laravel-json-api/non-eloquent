<?php
/*
 * Copyright 2022 Cloud Creativity Limited
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

namespace LaravelJsonApi\NonEloquent\Tests\Acceptance;

use App\Entities\SiteStorage;
use App\Entities\TagStorage;
use App\Entities\UserStorage;
use App\JsonApi\Sites\SiteSchema;
use App\JsonApi\Tags\TagSchema;
use App\JsonApi\Users\UserSchema;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDeprecationHandling;
use LaravelJsonApi\Contracts\Resources\Container as ResourceContainerContract;
use LaravelJsonApi\Contracts\Schema\Container as SchemaContainerContract;
use LaravelJsonApi\Contracts\Server\Server;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Resources\Container as ResourceContainer;
use LaravelJsonApi\Core\Resources\Factory;
use LaravelJsonApi\Core\Schema\Container as SchemaContainer;
use LaravelJsonApi\Core\Store\Store;
use LaravelJsonApi\Core\Support\ContainerResolver;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use InteractsWithDeprecationHandling;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutDeprecationHandling();

        $this->app->singleton(SiteStorage::class, static fn($container) => new SiteStorage(
            $container->make(UserStorage::class),
            $container->make(TagStorage::class),
            require __DIR__ . '/../../storage/sites.php'
        ));

        $this->app->singleton(UserStorage::class, static fn() => new UserStorage(
            require __DIR__ . '/../../storage/users.php'
        ));

        $this->app->singleton(TagStorage::class, static fn() => new TagStorage(
            require __DIR__ . '/../../storage/tags.php'
        ));

        $this->app->singleton(SchemaContainerContract::class, static function ($container) {
            $resolver = new ContainerResolver(static fn () => $container);
            return new SchemaContainer($resolver, $container->make(Server::class), [
                SiteSchema::class,
                TagSchema::class,
                UserSchema::class,
            ]);
        });

        $this->app->singleton(Server::class, function () {
            $server = $this->createMock(Server::class);
            $server->method('schemas')->willReturnCallback(fn() => $this->schemas());
            $server->method('resources')->willReturnCallback(fn() => $this->resources());
            $server->method('store')->willReturnCallback(fn() => $this->store());
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
     * @return ResourceContainerContract
     */
    protected function resources(): ResourceContainerContract
    {
        $factory = new Factory($this->schemas());

        return new ResourceContainer($factory);
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

    /**
     * @return UserStorage
     */
    protected function users(): UserStorage
    {
        return $this->app->make(UserStorage::class);
    }

    /**
     * @return TagStorage
     */
    protected function tags(): TagStorage
    {
        return $this->app->make(TagStorage::class);
    }
}
