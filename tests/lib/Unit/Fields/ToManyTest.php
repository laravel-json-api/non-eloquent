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

namespace LaravelJsonApi\NonEloquent\Tests\Unit\Fields;

use LaravelJsonApi\Contracts\Schema\Filter;
use LaravelJsonApi\NonEloquent\Fields\ToMany;
use PHPUnit\Framework\TestCase;

class ToManyTest extends TestCase
{

    public function test(): void
    {
        $field = ToMany::make('tags');

        $this->assertSame('tags', $field->name());
        $this->assertSame('tags', $field->inverse());
        $this->assertSame(['tags'], $field->allInverse());
        $this->assertSame('tags', $field->uriName());
        $this->assertFalse($field->toOne());
        $this->assertTrue($field->toMany());
    }

    public function testTypeAndTypes(): void
    {
        $field = ToMany::make('tags');

        $this->assertSame($field, $field->type('blog-tags'));
        $this->assertSame('blog-tags', $field->inverse());
        $this->assertSame(['blog-tags'], $field->allInverse());
        $this->assertSame($field, $field->types('site-tags', 'media-tags'));
        $this->assertSame('blog-tags', $field->inverse());
        $this->assertSame(['site-tags', 'media-tags'], $field->allInverse());
    }

    public function testUriName(): void
    {
        $field = ToMany::make('blogTags');

        $this->assertSame('blog-tags', $field->uriName());
        $this->assertSame($field, $field->retainFieldName());
        $this->assertSame('blogTags', $field->uriName());
        $this->assertSame($field, $field->withUriFieldName('some-other-name'));
        $this->assertSame('some-other-name', $field->uriName());
    }

    public function testIncludePath(): void
    {
        $this->assertTrue(ToMany::make('tags')->isIncludePath());
        $this->assertFalse(ToMany::make('tags')->cannotEagerLoad()->isIncludePath());
    }

    public function testFilterable(): void
    {
        $filter = $this->createMock(Filter::class);
        $attr = ToMany::make('tags');

        $this->assertSame([], $attr->filters());
        $this->assertSame($attr, $attr->withFilters($filter));
        $this->assertSame([$filter], $attr->filters());
    }

    public function testRequiredForValidation(): void
    {
        $this->assertFalse(ToMany::make('tags')->isValidated());
        $this->assertTrue(ToMany::make('tags')->mustValidate()->isValidated());
    }

    public function testSparseField(): void
    {
        $this->assertTrue(ToMany::make('tags')->isSparseField());
        $this->assertFalse(ToMany::make('tags')->notSparseField()->isSparseField());
    }
}
