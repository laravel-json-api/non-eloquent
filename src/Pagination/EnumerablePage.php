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

use Illuminate\Support\Enumerable;
use LaravelJsonApi\Core\Document\Link;
use LaravelJsonApi\Core\Pagination\AbstractPage;
use Traversable;

class EnumerablePage extends AbstractPage
{

    /**
     * @var Enumerable
     */
    private Enumerable $items;

    /**
     * @var int
     */
    private int $pageNumber;

    /**
     * @var int
     */
    private int $perPage;

    /**
     * @var int
     */
    private int $total;

    /**
     * @var int
     */
    private int $lastPage;

    /**
     * @var string
     */
    private string $path;

    /**
     * @var string
     */
    private string $pageParam = 'number';

    /**
     * @var string
     */
    private string $perPageParam = 'size';

    /**
     * EnumerablePage constructor.
     *
     * @param Enumerable $allItems
     * @param int $pageNumber
     * @param int $perPage
     * @param string $path
     */
    public function __construct(Enumerable $allItems, int $pageNumber, int $perPage, string $path)
    {
        $this->items = $allItems->forPage($pageNumber, $perPage)->values();
        $this->pageNumber = $pageNumber;
        $this->perPage = $perPage;
        $this->total = $allItems->count();
        $this->lastPage = max((int) ceil($this->total / $perPage), 1);
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function first(): ?Link
    {
        return new Link('first', $this->url(1));
    }

    /**
     * @inheritDoc
     */
    public function prev(): ?Link
    {
        if (1 < $this->pageNumber) {
            return new Link('prev', $this->url(
                $this->pageNumber - 1
            ));
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function next(): ?Link
    {
        if ($this->hasMorePages()) {
            return new Link('next', $this->url(
                $this->pageNumber + 1
            ));
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function last(): ?Link
    {
        return new Link('last', $this->url($this->lastPage));
    }

    /**
     * @param int $page
     * @return string
     */
    public function url(int $page): string
    {
        $params = $this->stringifyQuery([
            $this->pageParam => $page,
            $this->perPageParam => $this->perPage,
        ]);

        return $this->path . '?' . $params;
    }

    /**
     * Set the key for the page number parameter.
     *
     * @param string $key
     * @return $this
     */
    public function withPageParam(string $key): self
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('Page parameter cannot be an empty string.');
        }

        $this->pageParam = $key;

        return $this;
    }

    /**
     * Set the key for the per-page parameter.
     *
     * @param string $key
     * @return $this
     */
    public function withPerPageParam(string $key): self
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('Per-page parameter cannot be an empty string.');
        }

        $this->perPageParam = $key;

        return $this;
    }

    /**
     * Get the total number of items being paginated.
     *
     * @return int
     */
    public function total(): int
    {
        return $this->total;
    }

    /**
     * Determine if there are more items in the data source.
     *
     * @return bool
     */
    public function hasMorePages(): bool
    {
        return $this->pageNumber < $this->lastPage;
    }

    /**
     * Get the number of the first item in the slice.
     *
     * @return int|null
     */
    public function firstItem(): ?int
    {
        return count($this->items) > 0 ? ($this->pageNumber - 1) * $this->perPage + 1 : null;
    }

    /**
     * Get the number of the last item in the slice.
     *
     * @return int|null
     */
    public function lastItem(): ?int
    {
        return count($this->items) > 0 ? $this->firstItem() + $this->count() - 1 : null;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        yield from $this->items;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->items->count();
    }

    /**
     * @inheritDoc
     */
    protected function metaForPage(): array
    {
        return collect([
            'currentPage' => $this->pageNumber,
            'from' => $this->firstItem(),
            'lastPage' => $this->lastPage,
            'perPage' => $this->perPage,
            'to' => $this->lastItem(),
            'total' => $this->total,
        ])->reject(static fn ($value) => is_null($value))->all();
    }

}
