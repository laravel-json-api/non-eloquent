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
use LogicException;
use function is_array;
use function iterator_to_array;

class SiteStorage implements Countable
{

    /**
     * @var array
     */
    private array $sites = [];

    /**
     * SiteStorage constructor.
     *
     * @param array $sites
     */
    public function __construct(array $sites = [])
    {
        $this->load($sites);
    }

    /**
     * Load sites into the repository.
     *
     * @param iterable $sites
     * @return void
     */
    public function load(iterable $sites): void
    {
        foreach ($sites as $slug => $values) {
            if ($values instanceof Site) {
                $this->sites[$values->getSlug()] = $values->toArray();
                continue;
            }

            if (is_array($values)) {
                $this->sites[$slug] = $values;
                continue;
            }

            throw new LogicException('Expecting an iterable of sites entities or array values.');
        }

        ksort($this->sites);
    }

    /**
     * Find a site by its slug.
     *
     * @param string $slug
     * @return Site|null
     */
    public function find(string $slug): ?Site
    {
        if (isset($this->sites[$slug])) {
            return Site::fromArray($slug, $this->sites[$slug]);
        }

        return null;
    }

    /**
     * Does a site exist for the supplied slug?
     *
     * @param string $slug
     * @return bool
     */
    public function exists(string $slug): bool
    {
        return isset($this->sites[$slug]);
    }

    /**
     * @return Generator
     */
    public function cursor(): Generator
    {
        foreach ($this->sites as $slug => $values) {
            yield $slug => Site::fromArray($slug, $values);
        }
    }

    /**
     * Get all sites.
     *
     * @return array
     */
    public function all(): array
    {
        return iterator_to_array($this->cursor());
    }

    /**
     * Get all sites as a collection.
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
     * Store a site.
     *
     * @param Site $site
     * @return void
     */
    public function store(Site $site): void
    {
        $this->sites[$site->getSlug()] = $site->toArray();

        ksort($this->sites);
    }

    /**
     * Remove a site.
     *
     * @param Site|string $site
     * @return void
     */
    public function remove($site): void
    {
        if ($site instanceof Site) {
            $site = $site->getSlug();
        }

        if (is_string($site)) {
            unset($this->sites[$site]);
            return;
        }

        throw new InvalidArgumentException('Expecting a site object or string slug.');
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->sites);
    }

}
