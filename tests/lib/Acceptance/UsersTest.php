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

namespace LaravelJsonApi\NonEloquent\Tests\Acceptance;

use App\Entities\User;
use LaravelJsonApi\Contracts\Store\Store;

class UsersTest extends TestCase
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
        $this->assertInstanceOf(User::class, $this->store->find('users', 'john.doe'));
        $this->assertNull($this->store->find('users', 'foobar'));
    }

    public function testExists(): void
    {
        $this->assertTrue($this->store->exists('users', 'john.doe'));
        $this->assertFalse($this->store->exists('users', 'foobar'));
    }

    public function testFindMany(): void
    {
        $users = collect($this->store->findMany([
            ['type' => 'users', 'id' => 'jane.doe'],
            ['type' => 'users', 'id' => 'john.doe'],
            ['type' => 'users', 'id' => 'foobar'],
        ]));

        $this->assertCount(2, $users);
        $this->assertSame('jane.doe', $users[0]->getUsername());
        $this->assertSame('john.doe', $users[1]->getUsername());
    }

    public function testQueryOne(): void
    {
        $actual = $this->store->queryOne('users', 'john.doe')->first();

        $this->assertInstanceOf(User::class, $actual);
        $this->assertSame($actual, $this->store->queryOne('users', $actual)->first());
        $this->assertNull($this->store->queryOne('users', 'foobar')->first());
    }

    public function testCreate(): void
    {
        $expected = User::fromArray([
            'username' => 'frankie',
            'name' => 'Frankie Manning',
        ]);

        $actual = $this->store->create('users')->store([
            'username' => $expected->getUsername(),
            'name' => $expected->getName(),
        ]);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected, $this->users()->find($expected->getUsername()));
    }

    public function testUpdate(): void
    {
        $expected = $this->users()->find('john.doe');
        $expected->setName('Johnathan Doe');

        $actual = $this->store->update('users', 'john.doe')->store([
            'username' => $expected->getUsername(),
            'name' => $expected->getName(),
        ]);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected, $this->users()->find($expected->getUsername()));
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->users()->exists('john.doe'));

        $this->store->delete('users', 'john.doe');

        $this->assertFalse($this->sites()->exists('john.doe'));
    }

}
