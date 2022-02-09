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

namespace App\Entities;

use Illuminate\Contracts\Support\Arrayable;

class User implements Arrayable
{

    /**
     * @var string
     */
    private string $username;

    /**
     * @var string
     */
    private string $name;

    /**
     * Create a new user from an array.
     *
     * @param array $values
     * @return static
     */
    public static function fromArray(array $values): self
    {
        if (isset($values['username'], $values['name'])) {
            return new self($values['username'], $values['name']);
        }

        throw new \UnexpectedValueException('Array does not contain user values.');
    }

    /**
     * User constructor.
     *
     * @param string $username
     * @param string $name
     */
    public function __construct(string $username, string $name)
    {
        $this->username = $username;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            'username' => $this->getUsername(),
            'name' => $this->getName(),
        ];
    }

}
