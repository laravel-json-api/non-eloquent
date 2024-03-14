<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace LaravelJsonApi\NonEloquent\Concerns;

use LaravelJsonApi\Contracts\Pagination\Page;
use LaravelJsonApi\NonEloquent\Pagination\EnumerablePagination;
use RuntimeException;

trait PaginatesEnumerables
{

    /**
     * @inheritDoc
     */
    public function paginate(array $page): Page
    {
        $paginator = $this->schema()->pagination();

        if ($paginator instanceof EnumerablePagination) {
            return $paginator->paginate($this->get(), $page);
        }

        throw new RuntimeException('Expecting schema to return an enumerable paginator.');
    }

    /**
     * @inheritDoc
     */
    public function getOrPaginate(?array $page): iterable
    {
        if (empty($page)) {
            return $this->get();
        }

        return $this->paginate($page);
    }
}
