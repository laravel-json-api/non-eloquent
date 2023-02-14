<?php
/*
 * Copyright 2023 Cloud Creativity Limited
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

namespace LaravelJsonApi\NonEloquent\Tests\Acceptance;

use App\Entities\Site;
use App\Entities\Tag;
use App\Entities\User;
use LaravelJsonApi\Contracts\Store\Store;
use LaravelJsonApi\NonEloquent\Pagination\EnumerablePage;

class SitesTest extends TestCase
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
        $this->assertEquals(array_values($sites->all()), iterator_to_array($actual));
    }

    public function testQueryAllWithPagination(): void
    {
        $expected = $this->sites()->get()->forPage(2, 3);

        $actual = $this->store->queryAll('sites')->paginate(['number' => '2', 'size' => '3']);

        $this->assertInstanceOf(EnumerablePage::class, $actual);
        $this->assertCount(count($expected), $actual);
        $this->assertEquals(array_values($expected->all()), iterator_to_array($actual));
    }

    public function testQueryAllWithFilter(): void
    {
        $expected = $this->sites()->findMany(['example', 'laravel-json-api']);

        $filters = ['slugs' => ['example', 'laravel-json-api']];

        $actual = $this->store->queryAll('sites')->filter($filters)->get();

        $this->assertCount(2, $actual);
        $this->assertEquals($expected, iterator_to_array($actual));
    }

    public function testQueryAllWithSingularFilter(): void
    {
        $expected = $this->sites()->find('laravel-json-api');

        $filters = ['slug' => 'laravel-json-api'];

        $actual = $this->store->queryAll('sites')->filter($filters)->firstOrMany();

        $this->assertEquals($expected, $actual);
    }

    public function testQueryAllWithSingularFilterReturnsNull(): void
    {
        $filters = ['slug' => 'unexpected'];

        $actual = $this->store->queryAll('sites')->filter($filters)->firstOrMany();

        $this->assertNull($actual);
    }

    public function testQueryOne(): void
    {
        $actual = $this->store->queryOne('sites', 'google')->first();

        $this->assertInstanceOf(Site::class, $actual);
        $this->assertSame($actual, $this->store->queryOne('sites', $actual)->first());
        $this->assertNull($this->store->queryOne('sites', 'foobar')->first());
    }

    public function testQueryOneWithFilter(): void
    {
        $expected = $this->sites()->find('example');

        $actual = $this->store
            ->queryOne('sites', $expected->getSlug())
            ->filter(['name' => 'Example'])
            ->first();

        $this->assertEquals($expected, $actual);

        $actual = $this->store
            ->queryOne('sites', $expected)
            ->filter(['name' => 'Google'])
            ->first();

        $this->assertNull($actual);
    }

    public function testCreate(): void
    {
        $user = $this->users()->find('john.doe');
        $tag = $this->tags()->find('laravel');

        $expected = Site::fromArray('dancecloud', [
            'domain' => 'dancecloud.com',
            'name' => 'DanceCloud',
        ])->setOwner($user)->setTags($tag);

        $actual = $this->store->create('sites')->store([
            'domain' => $expected->getDomain(),
            'name' => $expected->getName(),
            'owner' => [
                'type' => 'users',
                'id' => $user->getUsername(),
            ],
            'slug' => $expected->getSlug(),
            'tags' => [
                ['type' => 'tags', 'id' => $tag->getSlug()],
            ],
        ]);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected, $this->sites()->find($expected->getSlug()));
    }

    public function testUpdate(): void
    {
        $tags = $this->tags()->all();

        $expected = $this->sites()->find('google');
        $expected->setName('Google (UK)');
        $expected->setDomain('google.co.uk');
        $expected->setTags(...array_values($tags));

        $actual = $this->store->update('sites', 'google')->store([
            'domain' => $expected->getDomain(),
            'name' => $expected->getName(),
            'slug' => $expected->getSlug(),
            'tags' => collect($tags)->map(fn(Tag $tag) => [
                'type' => 'tags',
                'id' => $tag->getSlug(),
            ])->all(),
        ]);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected, $this->sites()->find($expected->getSlug()));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->sites()->exists('google'));

        $this->store->delete('sites', 'google');

        $this->assertFalse($this->sites()->exists('google'));
    }

    public function testQueryToOne(): void
    {
        $site = $this->sites()->find('example');
        $expected = $site->getOwner();

        $this->assertInstanceOf(User::class, $expected);
        $this->assertEquals($expected, $this->store->queryToOne('sites', 'example', 'owner')->first());
        $this->assertEquals($expected, $this->store->queryToOne('sites', $site, 'owner')->first());
        $this->assertNull($this->store->queryToOne('sites', 'google', 'owner')->first());
    }

    public function testQueryToMany(): void
    {
        $site = $this->sites()->find('laravel-json-api');
        $expected = $site->getTags();

        $this->assertCount(2, $expected);
        $this->assertEquals($expected, $this->store->queryToMany('sites', 'laravel-json-api', 'tags')->get());
        $this->assertEquals($expected, $this->store->queryToMany('sites', $site, 'tags')->getOrPaginate(null));
        $this->assertEmpty($this->store->queryToMany('sites', 'google', 'tags')->get());
    }

    public function testModifyToOne(): void
    {
        $user = $this->users()->find('jane.doe');
        $site = $this->sites()->find('google');

        $this->assertNull($site->getOwner());

        $actual = $this->store()->modifyToOne('sites', 'google', 'owner')->associate([
            'type' => 'users',
            'id' => $user->getUsername(),
        ]);

        $this->assertEquals($user, $actual);
        $this->assertEquals($user, $this->sites()->find('google')->getOwner());
    }

    public function testSyncToMany(): void
    {
        $tags = $this->tags()->get()->values();
        $site = $this->sites()->find('google');

        $this->assertEmpty($site->getTags());

        $actual = $this->store()->modifyToMany('sites', 'google', 'tags')->sync(
            $tags->map(fn(Tag $tag) => [
                'type' => 'tags',
                'id' => $tag->getSlug(),
            ])->all()
        );

        $this->assertEquals($tags->all(), $actual);
        $this->assertEquals($tags->all(), $this->sites()->find('google')->getTags());
    }

    public function testAttachToMany(): void
    {
        $tags = collect($this->tags()->findMany(['test', 'laravel']));
        $site = $this->sites()->find('laravel-json-api');

        $this->assertEquals(['laravel', 'json-api'], $site->getTagIds());

        $actual = $this->store()->modifyToMany('sites', 'laravel-json-api', 'tags')->attach(
            $tags->map(fn(Tag $tag) => [
                'type' => 'tags',
                'id' => $tag->getSlug(),
            ])->all()
        );

        $this->assertEquals($tags->all(), $actual);
        $this->assertEquals(['laravel', 'json-api', 'test'], $this->sites()->find('laravel-json-api')->getTagIds());
    }

    public function testDetachToMany(): void
    {
        $tags = collect($this->tags()->findMany(['test', 'laravel']));
        $site = $this->sites()->find('laravel-json-api');

        $this->assertEquals(['laravel', 'json-api'], $site->getTagIds());

        $actual = $this->store()->modifyToMany('sites', 'laravel-json-api', 'tags')->detach(
            $tags->map(fn(Tag $tag) => [
                'type' => 'tags',
                'id' => $tag->getSlug(),
            ])->all()
        );

        $this->assertEquals($tags->all(), $actual);
        $this->assertEquals(['json-api'], $this->sites()->find('laravel-json-api')->getTagIds());
    }
}
