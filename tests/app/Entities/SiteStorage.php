<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

namespace App\Entities;

use Countable;
use Generator;
use Illuminate\Support\LazyCollection;
use InvalidArgumentException;
use function iterator_to_array;

class SiteStorage implements Countable
{

    /**
     * @var UserStorage
     */
    private UserStorage $users;

    /**
     * @var TagStorage
     */
    private TagStorage $tags;

    /**
     * @var array
     */
    private array $sites;

    /**
     * SiteStorage constructor.
     *
     * @param UserStorage $users
     * @param TagStorage $tags
     * @param array $sites
     */
    public function __construct(UserStorage $users, TagStorage $tags, array $sites = [])
    {
        $this->users = $users;
        $this->tags = $tags;
        $this->sites = $sites;

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
            return $this->make($slug, $this->sites[$slug]);
        }

        return null;
    }

    /**
     * Find sites by their slugs.
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
            $site = $this->make($slug, $values);
            yield $slug => $site;
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
    public function count(): int
    {
        return count($this->sites);
    }

    /**
     * Make a new site.
     *
     * @param string $slug
     * @param array $values
     * @return Site
     */
    private function make(string $slug, array $values): Site
    {
        $site = Site::fromArray($slug, $values);

        if (isset($values['owner_id'])) {
            $site->setOwner($this->users->find($values['owner_id']));
        }

        if (isset($values['tag_ids']) && is_array($values['tag_ids'])) {
            $site->setTags(...$this->tags->findMany($values['tag_ids']));
        }

        return $site;
    }

}
