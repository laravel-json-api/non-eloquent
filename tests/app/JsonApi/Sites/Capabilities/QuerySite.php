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
use LaravelJsonApi\Core\Support\Str;
use LaravelJsonApi\NonEloquent\Capabilities\QueryOne;

class QuerySite extends QueryOne
{

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
}
