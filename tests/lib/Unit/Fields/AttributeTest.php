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

use LaravelJsonApi\NonEloquent\Fields\Attribute;
use PHPUnit\Framework\TestCase;

class AttributeTest extends TestCase
{

    public function test(): void
    {
        $attr = Attribute::make('displayName');

        $this->assertSame('displayName', $attr->name());
        $this->assertFalse($attr->isSortable());
        $this->assertTrue($attr->isSparseField());
    }

    public function testSortable(): void
    {
        $attr = Attribute::make('displayName');

        $this->assertSame($attr, $attr->sortable());
        $this->assertTrue($attr->isSortable());
    }

    public function testSparseField(): void
    {
        $attr = Attribute::make('displayName');

        $this->assertSame($attr, $attr->notSparseField());
        $this->assertFalse($attr->isSparseField());
    }
}
