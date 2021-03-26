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

namespace App\JsonApi\Sites;

use App\Entities\Site;
use LaravelJsonApi\Contracts\Pagination\Paginator;
use LaravelJsonApi\Contracts\Store\Repository;
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
    public function pagination(): ?Paginator
    {
        return EnumerablePagination::make();
    }

    /**
     * @inheritDoc
     */
    public function repository(): Repository
    {
        return SiteRepository::make()
            ->withServer($this->server)
            ->withSchema($this);
    }

}
