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

namespace App\JsonApi\Users\Capabilities;

use App\Entities\User;
use App\Entities\UserStorage;
use LaravelJsonApi\NonEloquent\Capabilities\Crud;

class CrudUser extends Crud
{

    /**
     * @var UserStorage
     */
    private UserStorage $storage;

    /**
     * CrudUser constructor.
     *
     * @param UserStorage $storage
     */
    public function __construct(UserStorage $storage)
    {
        parent::__construct();
        $this->storage = $storage;
    }

    /**
     * Create a new user.
     *
     * @param array $validatedData
     * @return User
     */
    public function create(array $validatedData): User
    {
        $user = User::fromArray($validatedData);

        $this->storage->store($user);

        return $user;
    }

    /**
     * Update the supplied user.
     *
     * @param User $user
     * @param array $validatedData
     * @return void
     */
    public function update(User $user, array $validatedData): void
    {
        if (isset($validatedData['name'])) {
            $user->setName($validatedData['name']);
        }

        $this->storage->store($user);
    }

    /**
     * Delete the supplied user.
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user): void
    {
        $this->storage->remove($user);
    }
}
