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
