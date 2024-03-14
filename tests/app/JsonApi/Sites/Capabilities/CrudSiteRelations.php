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
use App\Entities\Tag;
use App\Entities\User;
use LaravelJsonApi\NonEloquent\Capabilities\CrudRelations;

class CrudSiteRelations extends CrudRelations
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
        $tags = collect($tags)->unique(
            fn(Tag $tag) => $tag->getSlug()
        );

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
            ->unique(fn(Tag $tag) => $tag->getSlug());

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
            ->reject(fn(Tag $tag) => $remove->contains($tag->getSlug()))
            ->unique(fn(Tag $tag) => $tag->getSlug());

        $site->setTags(...$all);

        $this->storage->store($site);
    }
}
