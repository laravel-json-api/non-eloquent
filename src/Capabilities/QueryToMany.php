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

namespace LaravelJsonApi\NonEloquent\Capabilities;

use LaravelJsonApi\Contracts\Store\QueryManyBuilder;
use LaravelJsonApi\Core\Query\Custom\ExtendedQueryParameters;
use LaravelJsonApi\NonEloquent\Concerns\HasModelOrResourceId;

abstract class QueryToMany extends Capability implements QueryManyBuilder
{

    use HasModelOrResourceId;

    /**
     * @var string
     */
    protected string $fieldName;

    /**
     * @inheritDoc
     */
    public function filter(?array $filters): QueryManyBuilder
    {
        $this->queryParameters = $this->queryParameters ?? new ExtendedQueryParameters();
        $this->queryParameters->setFilters($filters);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function sort($fields): QueryManyBuilder
    {
        $this->queryParameters = $this->queryParameters ?? new ExtendedQueryParameters();
        $this->queryParameters->setSortFields($fields);

        return $this;
    }

    /**
     * Set the field name that is being queried.
     *
     * @param string $fieldName
     * @return $this
     */
    public function withFieldName(string $fieldName): self
    {
        $this->fieldName = $fieldName;

        return $this;
    }

}
