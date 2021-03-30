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

namespace LaravelJsonApi\NonEloquent\Tests\Unit\Fields;

use LaravelJsonApi\NonEloquent\Fields\ID;
use PHPUnit\Framework\TestCase;

class IdTest extends TestCase
{

    public function test(): void
    {
        $id = ID::make();

        $this->assertSame('id', $id->name());
        $this->assertFalse($id->isSparseField());
        $this->assertNull($id->key());
    }

    public function testClientIds(): void
    {
        $this->assertFalse(ID::make()->acceptsClientIds());
        $this->assertTrue(ID::make()->clientIds()->acceptsClientIds());
    }

    public function testSortable(): void
    {
        $this->assertFalse(ID::make()->isSortable());
        $this->assertTrue(ID::make()->sortable()->isSortable());
    }

    public function testMatch(): void
    {
        $field = ID::make();

        $this->assertTrue($field->match('123'));
        $this->assertFalse($field->match('abc123'));

        $this->assertSame($field, $field->matchAs('[a-zA-Z]+'));

        $this->assertTrue($field->match('abcDEF'));
        $this->assertFalse($field->match('123'));
    }
}
