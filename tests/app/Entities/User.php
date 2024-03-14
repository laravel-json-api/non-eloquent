<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
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
