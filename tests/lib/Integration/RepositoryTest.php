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
use App\JsonApi\Sites\CrudSiteRepository;
use App\JsonApi\Sites\SiteRepository;
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

    /**
     * @return array
     */
    public function booleanProvider(): array
    {
        return [
            [true],
            [false],
        ];
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

    /**
     * @param bool $crud
     * @dataProvider booleanProvider
     */
    public function testQueryOne(bool $crud): void
    {
        $this->usingCrudRepository($crud);

        $actual = $this->store->queryOne('sites', 'google')->first();

        $this->assertInstanceOf(Site::class, $actual);
        $this->assertSame($actual, $this->store->queryOne('sites', $actual)->first());
        $this->assertNull($this->store->queryOne('sites', 'foobar')->first());
    }

    /**
     * @param bool $crud
     * @dataProvider booleanProvider
     */
    public function testCreate(bool $crud): void
    {
        $this->usingCrudRepository($crud);

        $expected = Site::fromArray('dancecloud', [
            'domain' => 'dancecloud.com',
            'name' => 'DanceCloud',
        ]);

        $actual = $this->store->create('sites')->store([
            'domain' => $expected->getDomain(),
            'name' => $expected->getName(),
            'slug' => $expected->getSlug(),
        ]);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected, $this->sites()->find($expected->getSlug()));
    }

    /**
     * @param bool $crud
     * @dataProvider booleanProvider
     */
    public function testUpdate(bool $crud): void
    {
        $this->usingCrudRepository($crud);

        $expected = $this->sites()->find('google');
        $expected->setName('Google (UK)');
        $expected->setDomain('google.co.uk');

        $actual = $this->store->update('sites', 'google')->store([
            'domain' => $expected->getDomain(),
            'name' => $expected->getName(),
            'slug' => $expected->getSlug(),
        ]);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected, $this->sites()->find($expected->getSlug()));
    }

    /**
     * @param bool $crud
     * @dataProvider booleanProvider
     */
    public function testDelete(bool $crud): void
    {
        $this->usingCrudRepository($crud);

        $this->assertTrue($this->sites()->exists('google'));

        $this->store->delete('sites', 'google');

        $this->assertFalse($this->sites()->exists('google'));
    }

    /**
     * Override the site repository to use the alternative CrudSiteRepository.
     *
     * @param bool $crud
     * @return void
     */
    private function usingCrudRepository(bool $crud = true): void
    {
        if ($crud) {
            $this->instance(SiteRepository::class, CrudSiteRepository::make());
        }
    }
}
