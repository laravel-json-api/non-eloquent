<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace App\JsonApi\Sites\Capabilities;

use App\Entities\Site;
use App\Entities\SiteStorage;
use App\Entities\User;
use LaravelJsonApi\Core\Support\Str;
use LaravelJsonApi\NonEloquent\Capabilities\CrudResource;

class CrudSite extends CrudResource
{

    /**
     * @var SiteStorage
     */
    private SiteStorage $storage;

    /**
     * CrudSite constructor.
     *
     * @param SiteStorage $storage
     */
    public function __construct(SiteStorage $storage)
    {
        parent::__construct();
        $this->storage = $storage;
    }

    /**
     * Create a new site.
     *
     * @param array $validatedData
     * @return Site
     */
    public function create(array $validatedData): Site
    {
        /** @var User|null $owner */
        $owner = $this->toOne($validatedData['owner'] ?? null);
        $tags = $this->toMany($validatedData['tags'] ?? []);

        $site = new Site($validatedData['slug']);
        $site->setDomain($validatedData['domain'] ?? null);
        $site->setName($validatedData['name'] ?? null);
        $site->setOwner($owner);
        $site->setTags(...$tags);

        $this->storage->store($site);

        return $site;
    }

    /**
     * Read the supplied site.
     *
     * @param Site $site
     * @return Site|null
     */
    public function read(Site $site): ?Site
    {
        $filters = $this->queryParameters->filter();

        if ($filters && $name = $filters->value('name')) {
            return Str::contains($site->getName(), $name) ? $site : null;
        }

        return $site;
    }

    /**
     * Update the site.
     *
     * @param Site $site
     * @param array $validatedData
     * @return Site
     */
    public function update(Site $site, array $validatedData): Site
    {
        if (array_key_exists('domain', $validatedData)) {
            $site->setDomain($validatedData['domain']);
        }

        if (array_key_exists('name', $validatedData)) {
            $site->setName($validatedData['name']);
        }

        if (array_key_exists('owner', $validatedData)) {
            $site->setOwner($this->toOne($validatedData['owner']));
        }

        if (isset($validatedData['tags'])) {
            $site->setTags(...$this->toMany($validatedData['tags']));
        }

        $this->storage->store($site);

        return $site;
    }

    /**
     * Delete the site.
     *
     * @param Site $site
     * @return void
     */
    public function delete(Site $site): void
    {
        $this->storage->remove($site);
    }
}
