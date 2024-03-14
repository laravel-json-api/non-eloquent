<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace LaravelJsonApi\NonEloquent\Capabilities;

use LaravelJsonApi\Contracts\Store\QueryManyBuilder;

abstract class QueryAll extends Capability implements QueryManyBuilder
{

    /**
     * @inheritDoc
     */
    public function filter(?array $filters): QueryAll
    {
        $this->queryParameters->setFilters($filters);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function sort($fields): QueryAll
    {
        $this->queryParameters->setSortFields($fields);

        return $this;
    }

}
