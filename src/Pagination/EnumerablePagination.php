<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace LaravelJsonApi\NonEloquent\Pagination;

use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use LaravelJsonApi\Contracts\Pagination\Paginator;
use LaravelJsonApi\Core\Pagination\Concerns\HasPageMeta;
use LaravelJsonApi\Core\Pagination\Concerns\HasPageNumbers;

class EnumerablePagination implements Paginator
{

    use HasPageMeta;
    use HasPageNumbers;

    /**
     * Create a new paginator.
     *
     * @return static
     */
    public static function make(): self
    {
        return new self();
    }

    /**
     * Paginate the provided items using JSON:API page parameters.
     *
     * @param Enumerable|iterable $allItems
     * @param array $page
     * @return EnumerablePage
     */
    public function paginate($allItems, array $page): EnumerablePage
    {
        if (!$allItems instanceof Enumerable) {
            $allItems = Collection::make($allItems);
        }

        $pageNumber = $page[$this->pageKey] ?? 1;
        $perPage = $page[$this->perPageKey] ?? $this->defaultPerPage;

        $page = new EnumerablePage(
            $allItems,
            intval($pageNumber),
            intval($perPage ?? 15),
            AbstractPaginator::resolveCurrentPath(),
        );

        return $page
            ->withPageParam($this->pageKey)
            ->withPerPageParam($this->perPageKey)
            ->withMeta($this->hasMeta)
            ->withNestedMeta($this->metaKey)
            ->withMetaCase($this->metaCase);
    }
}
