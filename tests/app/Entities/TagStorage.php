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

namespace App\Entities;

use Countable;
use Generator;
use Illuminate\Support\LazyCollection;
use InvalidArgumentException;
use function is_array;
use function iterator_to_array;

class TagStorage implements Countable
{

    /**
     * @var array
     */
    private array $tags = [];

    /**
     * SiteStorage constructor.
     *
     * @param array $tags
     */
    public function __construct(array $tags = [])
    {
        foreach ($tags as $values) {
            $this->tags[$values['slug']] = $values;
        }

        ksort($this->tags);
    }

    /**
     * Find a tag by its slug.
     *
     * @param string $slug
     * @return Tag|null
     */
    public function find(string $slug): ?Tag
    {
        $values = $this->tags[$slug] ?? null;

        if (is_array($values)) {
            return Tag::fromArray($values);
        }

        return null;
    }

    /**
     * Find tags by their slugs.
     *
     * @param array $slugs
     * @return array
     */
    public function findMany(array $slugs): array
    {
        return collect($slugs)->map(
            fn($slug) => $this->find($slug)
        )->filter()->values()->all();
    }

    /**
     * Does a tag exist for the supplied slug?
     *
     * @param string $slug
     * @return bool
     */
    public function exists(string $slug): bool
    {
        return isset($this->tags[$slug]);
    }

    /**
     * @return Generator
     */
    public function cursor(): Generator
    {
        foreach ($this->tags as $values) {
            $tag = Tag::fromArray($values);
            yield $tag->getSlug() => $tag;
        }
    }

    /**
     * Get all tags.
     *
     * @return array
     */
    public function all(): array
    {
        return iterator_to_array($this->cursor());
    }

    /**
     * Get all tags as a collection.
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
     * Store a tag.
     *
     * @param Tag $tag
     * @return void
     */
    public function store(Tag $tag): void
    {
        $this->tags[$tag->getSlug()] = $tag->toArray();

        ksort($this->tags);
    }

    /**
     * Remove a tag.
     *
     * @param Tag|string $tag
     * @return void
     */
    public function remove($tag): void
    {
        if ($tag instanceof Tag) {
            $tag = $tag->getSlug();
        }

        if (is_string($tag)) {
            unset($this->tags[$tag]);
            return;
        }

        throw new InvalidArgumentException('Expecting a tag object or string slug.');
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->tags);
    }

}
