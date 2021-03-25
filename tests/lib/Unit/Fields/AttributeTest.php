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
