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

class Tag implements Arrayable
{

    /**
     * @var string
     */
    private string $slug;

    /**
     * @var string
     */
    private string $displayName;

    /**
     * Create a new tag from the provided array.
     *
     * @param array $values
     * @return static
     */
    public static function fromArray(array $values): self
    {
        if (isset($values['slug'], $values['display_name'])) {
            return new self($values['slug'], $values['display_name']);
        }

        throw new \UnexpectedValueException('Array does not contain tag values.');
    }

    /**
     * Tag constructor.
     *
     * @param string $slug
     * @param string $displayName
     */
    public function __construct(string $slug, string $displayName)
    {
        $this->slug = $slug;
        $this->displayName = $displayName;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     * @return $this
     */
    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            'slug' => $this->getSlug(),
            'display_name' => $this->getDisplayName(),
        ];
    }

}
