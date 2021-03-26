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
use LaravelJsonApi\NonEloquent\Capabilities\CreateResource;

class CreateSite extends CreateResource
{

    /**
     * @var SiteStorage
     */
    private SiteStorage $storage;

    /**
     * CreateSite constructor.
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

}
