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

use InvalidArgumentException;
use LaravelJsonApi\Contracts\Store\QueryManyBuilder;
use LaravelJsonApi\Core\Query\Custom\ExtendedQueryParameters;
use function is_object;
use function is_string;

abstract class QueryToMany extends Capability implements QueryManyBuilder
{

    /**
     * @var string|object
     */
    protected $modelOrResourceId;

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
     * Set the model or resource id that is being queried.
     *
     * @param string|object $modelOrResourceId
     * @return $this
     */
    public function withModelOrResourceId($modelOrResourceId): self
    {
        if (!is_string($modelOrResourceId) && !is_object($modelOrResourceId)) {
            throw new InvalidArgumentException('Expecting a string or object.');
        }

        $this->modelOrResourceId = $modelOrResourceId;

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
