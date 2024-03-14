<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace App\JsonApi\Users\Capabilities;

use App\Entities\User;
use App\Entities\UserStorage;
use LaravelJsonApi\NonEloquent\Capabilities\CrudResource;

class CrudUser extends CrudResource
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
