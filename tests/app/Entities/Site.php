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

use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;

class Site implements Arrayable
{

    /**
     * @var string
     */
    private string $slug;

    /**
     * @var string|null
     */
    private ?string $domain;

    /**
     * @var string|null
     */
    private ?string $name;

    /**
     * @var User|null
     */
    private ?User $owner = null;

    /**
     * @var Tag[]
     */
    private array $tags = [];

    /**
     * Create a new site entity from an array.
     *
     * @param string $slug
     * @param array $values
     * @return Site
     */
    public static function fromArray(string $slug, array $values)
    {
        $site = new self($slug);
        $site->setDomain($values['domain'] ?? null);
        $site->setName($values['name'] ?? null);

        return $site;
    }

    /**
     * Site constructor.
     *
     * @param string $slug
     */
    public function __construct(string $slug)
    {
        if (empty($slug)) {
            throw new InvalidArgumentException('Expecting a non-empty slug');
        }

        $this->slug = $slug;
    }

    /**
     * Get the site slug.
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Get the site domain.
     *
     * @param string|null $domain
     * @return $this
     */
    public function setDomain(?string $domain): self
    {
        $this->domain = $domain ?: null;

        return $this;
    }

    /**
     * Get the site domain.
     *
     * @return string|null
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * Set the site name.
     *
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name ?: null;

        return $this;
    }

    /**
     * Get the site's name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setOwner(?User $user): self
    {
        $this->owner = $user;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * @return bool
     */
    public function hasOwner(): bool
    {
        return $this->owner instanceof User;
    }

    /**
     * @param Tag ...$tags
     * @return $this
     */
    public function setTags(Tag ...$tags): self
    {
        $this->tags = array_values($tags);

        return $this;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return bool
     */
    public function hasTags(): bool
    {
        return !empty($this->tags);
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        $values = [
            'domain' => $this->getDomain(),
            'name' => $this->getName(),
        ];

        if ($this->hasOwner()) {
            $values['owner_id'] = $this
                ->getOwner()
                ->getUsername();
        }

        if ($this->hasTags()) {
            $values['tag_ids'] = collect($this->getTags())->map(
                fn(Tag $tag) => $tag->getSlug()
            )->all();
        }

        return $values;
    }

}
