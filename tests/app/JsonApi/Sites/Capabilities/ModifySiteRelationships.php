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
use App\Entities\Tag;
use App\Entities\User;
use LaravelJsonApi\NonEloquent\Capabilities\ModifyRelations;

class ModifySiteRelationships extends ModifyRelations
{

    /**
     * @var SiteStorage
     */
    private SiteStorage $storage;

    /**
     * ModifySiteRelationships constructor.
     *
     * @param SiteStorage $storage
     */
    public function __construct(SiteStorage $storage)
    {
        parent::__construct();
        $this->storage = $storage;
    }

    /**
     * Set the owner relationship.
     *
     * @param Site $site
     * @param User|null $user
     * @return void
     */
    public function setOwner(Site $site, ?User $user): void
    {
        $site->setOwner($user);

        $this->storage->store($site);
    }

    /**
     * Set the tags relationship.
     *
     * @param Site $site
     * @param array $tags
     */
    public function setTags(Site $site, array $tags): void
    {
        $site->setTags(...$tags);

        $this->storage->store($site);
    }

    /**
     * Attach tags to the provided site.
     *
     * @param Site $site
     * @param array $tags
     */
    public function attachTags(Site $site, array $tags): void
    {
        $all = collect($site->getTags())
            ->merge($tags)
            ->unique(fn (Tag $tag) => $tag->getSlug());

        $site->setTags(...$all);

        $this->storage->store($site);
    }

    /**
     * Detach tags from the provided site.
     *
     * @param Site $site
     * @param array $tags
     * @return void
     */
    public function detachTags(Site $site, array $tags): void
    {
        $remove = collect($tags)
            ->map(fn(Tag $tag) => $tag->getSlug());

        $all = collect($site->getTags())
            ->reject(fn(Tag $tag) => $remove->contains($tag->getSlug()));

        $site->setTags(...$all);

        $this->storage->store($site);
    }
}
