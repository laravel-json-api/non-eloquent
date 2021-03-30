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
use LaravelJsonApi\Contracts\Store\HasPagination;
use LaravelJsonApi\Contracts\Store\HasSingularFilters;
use LaravelJsonApi\NonEloquent\Capabilities\QueryAll;
use LaravelJsonApi\NonEloquent\Concerns\PaginatesEnumerables;

class QuerySites extends QueryAll implements HasPagination, HasSingularFilters
{

    use PaginatesEnumerables;

    /**
     * @var SiteStorage
     */
    private SiteStorage $sites;

    /**
     * QuerySites constructor.
     *
     * @param SiteStorage $sites
     */
    public function __construct(SiteStorage $sites)
    {
        parent::__construct();
        $this->sites = $sites;
    }

    /**
     * @inheritDoc
     */
    public function get(): iterable
    {
        $sites = $this->sites->get();
        $filters = $this->queryParameters->filter();

        if ($filters && is_array($slugs = $filters->value('slugs'))) {
            $sites = $sites->filter(
                fn(Site $site) => in_array($site->getSlug(), $slugs)
            );
        }

        return $sites->values();
    }

    /**
     * @inheritDoc
     */
    public function firstOrMany()
    {
        $filters = $this->queryParameters->filter();

        if ($filters && $filters->exists('slug')) {
            return $this->sites->find(
                $filters->value('slug')
            );
        }

        return $this->get();
    }

    /**
     * @inheritDoc
     */
    public function firstOrPaginate(?array $page)
    {
        if (empty($page)) {
            return $this->firstOrMany();
        }

        return $this->paginate($page);
    }

}
