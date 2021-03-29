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

namespace App\JsonApi\Users;

use App\Entities\UserStorage;
use App\JsonApi\Users\Capabilities\CrudUser;
use LaravelJsonApi\Contracts\Store\CreatesResources;
use LaravelJsonApi\Contracts\Store\DeletesResources;
use LaravelJsonApi\Contracts\Store\UpdatesResources;
use LaravelJsonApi\NonEloquent\AbstractRepository;
use LaravelJsonApi\NonEloquent\Concerns\HasCrudCapability;

class UserRepository extends AbstractRepository implements CreatesResources, UpdatesResources, DeletesResources
{

    use HasCrudCapability;

    /**
     * @var UserStorage
     */
    private UserStorage $storage;

    /**
     * UserRepository constructor.
     *
     * @param UserStorage $storage
     */
    public function __construct(UserStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public function find(string $resourceId): ?object
    {
        return $this->storage->find($resourceId);
    }

    /**
     * @inheritDoc
     */
    protected function crud(): CrudUser
    {
        return CrudUser::make($this->storage);
    }


}
