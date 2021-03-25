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

namespace App\Entities;

use Countable;
use Generator;
use Illuminate\Support\LazyCollection;
use InvalidArgumentException;
use function is_array;
use function iterator_to_array;

class UserStorage implements Countable
{

    /**
     * @var array
     */
    private array $users = [];

    /**
     * SiteStorage constructor.
     *
     * @param array $users
     */
    public function __construct(array $users = [])
    {
        foreach ($users as $values) {
            $this->users[$values['username']] = $values;
        }

        ksort($this->users);
    }

    /**
     * Find a user by its username.
     *
     * @param string $username
     * @return User|null
     */
    public function find(string $username): ?User
    {
        $values = $this->users[$username] ?? null;

        if (is_array($values)) {
            return User::fromArray($values);
        }

        return null;
    }

    /**
     * Does a user exist for the supplied username?
     *
     * @param string $username
     * @return bool
     */
    public function exists(string $username): bool
    {
        return isset($this->users[$username]);
    }

    /**
     * @return Generator
     */
    public function cursor(): Generator
    {
        foreach ($this->users as $values) {
            $user = User::fromArray($values);
            yield $user->getUsername() => $user;
        }
    }

    /**
     * Get all users.
     *
     * @return array
     */
    public function all(): array
    {
        return iterator_to_array($this->cursor());
    }

    /**
     * Get all users as a collection.
     *
     * @return LazyCollection
     */
    public function get(): LazyCollection
    {
        return new LazyCollection(function () {
            yield from $this->cursor();
        });
    }

    /**
     * Store a user.
     *
     * @param User $user
     * @return void
     */
    public function store(User $user): void
    {
        $this->users[$user->getUsername()] = $user->toArray();

        ksort($this->users);
    }

    /**
     * Remove a user.
     *
     * @param User|string $user
     * @return void
     */
    public function remove($user): void
    {
        if ($user instanceof User) {
            $user = $user->getUsername();
        }

        if (is_string($user)) {
            unset($this->users[$user]);
            return;
        }

        throw new InvalidArgumentException('Expecting a user object or string username.');
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->users);
    }

}
