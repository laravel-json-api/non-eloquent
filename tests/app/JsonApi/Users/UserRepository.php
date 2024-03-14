<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
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
