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
use function array_key_exists;

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
     * Create a new site entity from an array.
     *
     * @param string $slug
     * @param array $values
     * @return Site
     */
    public static function fromArray(string $slug, array $values)
    {
        $site = new self($slug);
        $site->exchangeArray($values);

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
     * Set values using an array.
     *
     * @param array $values
     * @return $this
     */
    public function exchangeArray(array $values): self
    {
        if (array_key_exists('domain', $values)) {
            $this->setDomain($values['domain']);
        }

        if (array_key_exists('name', $values)) {
            $this->setName($values['name']);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            'domain' => $this->getDomain(),
            'name' => $this->getName(),
        ];
    }

}
