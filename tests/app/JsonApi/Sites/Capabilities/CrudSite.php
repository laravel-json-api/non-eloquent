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
