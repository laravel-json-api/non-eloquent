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

namespace LaravelJsonApi\NonEloquent\Tests\Integration\Pagination;

use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use LaravelJsonApi\Core\Support\Arr;
use LaravelJsonApi\NonEloquent\Pagination\EnumerablePage;
use LaravelJsonApi\NonEloquent\Pagination\EnumerablePagination;
use PHPUnit\Framework\TestCase;

class EnumerablePaginationTest extends TestCase
{

    /**
     * @var Collection
     */
    private Collection $items;

    /**
     * @var EnumerablePagination
     */
    private EnumerablePagination $paginator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->items = Collection::make(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l']);

        $this->paginator = EnumerablePagination::make();

        AbstractPaginator::currentPathResolver(fn() => '/api/v1/sites');
    }

    public function testPage1(): void
    {
        $this->assertSame(['number', 'size'], $this->paginator->keys());

        $meta = [
            'currentPage' => 1,
            'from' => 1,
            'lastPage' => 3,
            'perPage' => 5,
            'to' => 5,
            'total' => 12,
        ];

        $links = [
            'first' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 1, 'size' => 5],
                    ]),
            ],
            'last' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 3, 'size' => 5],
                    ]),
            ],
            'next' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 2, 'size' => 5],
                    ]),
            ],
        ];

        $page = $this->paginator->paginate($this->items, ['number' => '1', 'size' => '5']);

        $this->assertInstanceOf(EnumerablePage::class, $page);
        $this->assertEquals($this->items->forPage(1, 5)->all(), iterator_to_array($page));
        $this->assertEquals(['page' => $meta], $page->meta());
        $this->assertEquals($links, $page->links()->toArray());
    }

    public function testPage2(): void
    {
        $meta = [
            'currentPage' => 2,
            'from' => 6,
            'lastPage' => 3,
            'perPage' => 5,
            'to' => 10,
            'total' => 12,
        ];

        $links = [
            'first' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 1, 'size' => 5],
                    ]),
            ],
            'last' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 3, 'size' => 5],
                    ]),
            ],
            'next' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 3, 'size' => 5],
                    ]),
            ],
            'prev' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 1, 'size' => 5],
                    ]),
            ],
        ];

        $page = $this->paginator->paginate($this->items, ['number' => '2', 'size' => '5']);

        $this->assertInstanceOf(EnumerablePage::class, $page);
        $this->assertEquals($this->items->forPage(2, 5)->all(), iterator_to_array($page));
        $this->assertEquals(['page' => $meta], $page->meta());
        $this->assertEquals($links, $page->links()->toArray());
    }

    public function testPage3(): void
    {
        $meta = [
            'currentPage' => 3,
            'from' => 11,
            'lastPage' => 3,
            'perPage' => 5,
            'to' => 12,
            'total' => 12,
        ];

        $links = [
            'first' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 1, 'size' => 5],
                    ]),
            ],
            'last' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 3, 'size' => 5],
                    ]),
            ],
            'prev' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 2, 'size' => 5],
                    ]),
            ],
        ];

        $page = $this->paginator->paginate($this->items, ['number' => '3', 'size' => '5']);

        $this->assertInstanceOf(EnumerablePage::class, $page);
        $this->assertEquals($this->items->forPage(3, 5)->all(), iterator_to_array($page));
        $this->assertEquals(['page' => $meta], $page->meta());
        $this->assertEquals($links, $page->links()->toArray());
    }

    public function testPage4(): void
    {
        $meta = [
            'currentPage' => 4,
            'lastPage' => 3,
            'perPage' => 5,
            'total' => 12,
        ];

        $links = [
            'first' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 1, 'size' => 5],
                    ]),
            ],
            'last' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 3, 'size' => 5],
                    ]),
            ],
            'prev' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 3, 'size' => 5],
                    ]),
            ],
        ];

        $page = $this->paginator->paginate($this->items, ['number' => '4', 'size' => '5']);

        $this->assertInstanceOf(EnumerablePage::class, $page);
        $this->assertEquals([], iterator_to_array($page));
        $this->assertEquals(['page' => $meta], $page->meta());
        $this->assertEquals($links, $page->links()->toArray());
    }

    public function testDefaultPageSize(): void
    {
        $meta = [
            'currentPage' => 1,
            'from' => 1,
            'lastPage' => 1,
            'perPage' => 15,
            'to' => 12,
            'total' => 12,
        ];

        $links = [
            'first' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 1, 'size' => 15],
                    ]),
            ],
            'last' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 1, 'size' => 15],
                    ]),
            ],
        ];

        $page = $this->paginator->paginate($this->items, ['number' => '1', 'size' => null]);

        $this->assertInstanceOf(EnumerablePage::class, $page);
        $this->assertEquals($this->items->all(), iterator_to_array($page));
        $this->assertEquals(['page' => $meta], $page->meta());
        $this->assertEquals($links, $page->links()->toArray());
    }

    public function testCustomDefaultPageSize(): void
    {
        $this->paginator->withDefaultPerPage(25);

        $meta = [
            'currentPage' => 1,
            'from' => 1,
            'lastPage' => 1,
            'perPage' => 25,
            'to' => 12,
            'total' => 12,
        ];

        $links = [
            'first' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 1, 'size' => 25],
                    ]),
            ],
            'last' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['number' => 1, 'size' => 25],
                    ]),
            ],
        ];

        $page = $this->paginator->paginate($this->items, ['number' => '1']);

        $this->assertInstanceOf(EnumerablePage::class, $page);
        $this->assertEquals($this->items->all(), iterator_to_array($page));
        $this->assertEquals(['page' => $meta], $page->meta());
        $this->assertEquals($links, $page->links()->toArray());
    }

    public function testWithCustomKeys(): void
    {
        $this->paginator->withPageKey('page')->withPerPageKey('limit');

        $this->assertSame(['page', 'limit'], $this->paginator->keys());

        $links = [
            'first' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['limit' => 5, 'page' => 1],
                    ]),
            ],
            'last' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['limit' => 5, 'page' => 3],
                    ]),
            ],
            'next' => [
                'href' => '/api/v1/sites?' . Arr::query([
                        'page' => ['limit' => 5, 'page' => 2],
                    ]),
            ],
        ];

        $page = $this->paginator->paginate($this->items, ['page' => '1', 'limit' => '5']);

        $this->assertInstanceOf(EnumerablePage::class, $page);
        $this->assertEquals($this->items->forPage(1, 5)->all(), iterator_to_array($page));
        $this->assertEquals($links, $page->links()->toArray());
    }

    public function testMetaNotNested(): void
    {
        $this->paginator->withoutNestedMeta();

        $meta = [
            'currentPage' => 1,
            'from' => 1,
            'lastPage' => 3,
            'perPage' => 5,
            'to' => 5,
            'total' => 12,
        ];

        $page = $this->paginator->paginate($this->items, ['number' => '1', 'size' => '5']);

        $this->assertEquals($meta, $page->meta());
    }

    public function testCustomMetaNestingKey(): void
    {
        $this->paginator->withMetaKey('paginator');

        $meta = [
            'currentPage' => 1,
            'from' => 1,
            'lastPage' => 3,
            'perPage' => 5,
            'to' => 5,
            'total' => 12,
        ];

        $page = $this->paginator->paginate($this->items, ['number' => '1', 'size' => '5']);

        $this->assertEquals(['paginator' => $meta], $page->meta());
    }

    public function testSnakeCaseMeta(): void
    {
        $this->paginator->withSnakeCaseMeta();

        $meta = [
            'current_page' => 1,
            'from' => 1,
            'last_page' => 3,
            'per_page' => 5,
            'to' => 5,
            'total' => 12,
        ];

        $page = $this->paginator->paginate($this->items, ['number' => '1', 'size' => '5']);

        $this->assertEquals(['page' => $meta], $page->meta());
    }

    public function testDashCaseMeta(): void
    {
        $this->paginator->withDashCaseMeta();

        $meta = [
            'current-page' => 1,
            'from' => 1,
            'last-page' => 3,
            'per-page' => 5,
            'to' => 5,
            'total' => 12,
        ];

        $page = $this->paginator->paginate($this->items, ['number' => '1', 'size' => '5']);

        $this->assertEquals(['page' => $meta], $page->meta());
    }

}
