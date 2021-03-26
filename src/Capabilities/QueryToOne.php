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

use LaravelJsonApi\Contracts\Store\QueryOneBuilder;
use LaravelJsonApi\Core\Query\Custom\ExtendedQueryParameters;
use LaravelJsonApi\Core\Support\Str;
use LaravelJsonApi\NonEloquent\Concerns\HasModelResourceIdAndFieldName;
use RuntimeException;
use function is_null;
use function is_object;
use function method_exists;
use function sprintf;

class QueryToOne extends Capability implements QueryOneBuilder
{

    use HasModelResourceIdAndFieldName;

    /**
     * @inheritDoc
     */
    public function filter(?array $filters): QueryOneBuilder
    {
        $this->queryParameters = $this->queryParameters ?? new ExtendedQueryParameters();
        $this->queryParameters->setFilters($filters);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function first(): ?object
    {
        $method = 'get' . Str::classify($this->fieldName);

        if (method_exists($this, $method)) {
            return $this->{$method}($this->modelOrFail());
        }

        $value = $this->value();

        if (is_object($value) || is_null($value)) {
            return $value;
        }

        throw new RuntimeException(sprintf(
            'Expecting resource to return an object or null for relation %s.',
            $this->fieldName,
        ));
    }

}
