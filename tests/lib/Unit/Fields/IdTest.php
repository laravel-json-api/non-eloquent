<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
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
