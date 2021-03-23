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

namespace LaravelJsonApi\NonEloquent\Tests\Integration;

use App\Entities\Site;
use LaravelJsonApi\Contracts\Store\Store;

class RepositoryTest extends TestCase
{

    /**
     * @var Store
     */
    private Store $store;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->store = $this->store();
    }

    public function testFind(): void
    {
        $this->assertInstanceOf(Site::class, $this->store->find('sites', 'google'));
        $this->assertNull($this->store->find('sites', 'foobar'));
    }

    public function testExists(): void
    {
        $this->assertTrue($this->store->exists('sites', 'google'));
        $this->assertFalse($this->store->exists('sites', 'foobar'));
    }

    public function testFindMany(): void
    {
        $sites = collect($this->store->findMany([
            ['type' => 'sites', 'id' => 'google'],
            ['type' => 'sites', 'id' => 'facebook'],
            ['type' => 'sites', 'id' => 'foobar'],
        ]));

        $this->assertCount(2, $sites);
        $this->assertSame('google', $sites[0]->getSlug());
        $this->assertSame('facebook', $sites[1]->getSlug());
    }

    public function testQueryAll(): void
    {
        $sites = $this->sites();
        $actual = $this->store->queryAll('sites')->get();

        $this->assertCount(count($sites), $actual);
        $this->assertEquals($sites->all(), iterator_to_array($actual));
    }

    public function testQueryOne(): void
    {
        $actual = $this->store->queryOne('sites', 'google')->first();

        $this->assertInstanceOf(Site::class, $actual);
        $this->assertSame($actual, $this->store->queryOne('sites', $actual)->first());
        $this->assertNull($this->store->queryOne('sites', 'foobar')->first());
    }
}
