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

use LaravelJsonApi\Contracts\Schema\Filter;
use LaravelJsonApi\NonEloquent\Fields\ToOne;
use PHPUnit\Framework\TestCase;

class ToOneTest extends TestCase
{

    public function test(): void
    {
        $field = ToOne::make('user');

        $this->assertSame('user', $field->name());
        $this->assertSame('users', $field->inverse());
        $this->assertSame(['users'], $field->allInverse());
        $this->assertSame('user', $field->uriName());
        $this->assertTrue($field->toOne());
        $this->assertFalse($field->toMany());
    }

    public function testTypeAndTypes(): void
    {
        $field = ToOne::make('user');

        $this->assertSame($field, $field->type('super-users'));
        $this->assertSame('super-users', $field->inverse());
        $this->assertSame(['super-users'], $field->allInverse());
        $this->assertSame($field, $field->types('super', 'users'));
        $this->assertSame('super-users', $field->inverse());
        $this->assertSame(['super', 'users'], $field->allInverse());
    }

    public function testUriName(): void
    {
        $field = ToOne::make('superUser');

        $this->assertSame('super-user', $field->uriName());
        $this->assertSame($field, $field->retainFieldName());
        $this->assertSame('superUser', $field->uriName());
        $this->assertSame($field, $field->withUriFieldName('some-other-name'));
        $this->assertSame('some-other-name', $field->uriName());
    }

    public function testIncludePath(): void
    {
        $this->assertTrue(ToOne::make('user')->isIncludePath());
        $this->assertFalse(ToOne::make('user')->cannotEagerLoad()->isIncludePath());
    }

    public function testFilterable(): void
    {
        $filter = $this->createMock(Filter::class);
        $attr = ToOne::make('user');

        $this->assertSame([], $attr->filters());
        $this->assertSame($attr, $attr->withFilters($filter));
        $this->assertSame([$filter], $attr->filters());
    }

    public function testRequiredForValidation(): void
    {
        $this->assertFalse(ToOne::make('user')->isValidated());
        $this->assertTrue(ToOne::make('user')->mustValidate()->isValidated());
    }

    public function testSparseField(): void
    {
        $this->assertTrue(ToOne::make('user')->isSparseField());
        $this->assertFalse(ToOne::make('user')->notSparseField()->isSparseField());
    }
}
