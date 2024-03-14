<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace App\JsonApi\Sites;

use App\Entities\Site;
use LaravelJsonApi\Core\Schema\Schema;
use LaravelJsonApi\NonEloquent\Fields\Attribute;
use LaravelJsonApi\NonEloquent\Fields\ID;
use LaravelJsonApi\NonEloquent\Fields\ToMany;
use LaravelJsonApi\NonEloquent\Fields\ToOne;
use LaravelJsonApi\NonEloquent\Filters\Filter;
use LaravelJsonApi\NonEloquent\Pagination\EnumerablePagination;

class SiteSchema extends Schema
{

    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = Site::class;

    /**
     * @inheritDoc
     */
    public function fields(): iterable
    {
        return [
            ID::make(),
            Attribute::make('domain'),
            Attribute::make('name'),
            ToOne::make('owner')->type('users'),
            ToMany::make('tags'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function filters(): iterable
    {
        return [
            Filter::make('slug'),
            Filter::make('slugs'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function pagination(): EnumerablePagination
    {
        return EnumerablePagination::make();
    }

    /**
     * @inheritDoc
     */
    public function repository(): SiteRepository
    {
        return SiteRepository::make()
            ->withServer($this->server)
            ->withSchema($this);
    }

}
