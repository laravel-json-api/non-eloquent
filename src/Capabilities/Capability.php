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

use Illuminate\Http\Request;
use LaravelJsonApi\Contracts\Query\QueryParameters;
use LaravelJsonApi\Contracts\Store\Builder;
use LaravelJsonApi\Core\Query\Custom\ExtendedQueryParameters;

abstract class Capability implements Builder
{

    /**
     * @var Request|null
     */
    protected ?Request $request = null;

    /**
     * @var ExtendedQueryParameters|null
     */
    protected ?ExtendedQueryParameters $queryParameters = null;

    /**
     * Fluent constructor.
     *
     * @param mixed ...$arguments
     * @return static
     */
    public static function make(...$arguments): self
    {
        return new static(...$arguments);
    }

    /**
     * @inheritDoc
     */
    public function withRequest(Request $request): Builder
    {
        $this->request = $request;
        $this->queryParameters = ExtendedQueryParameters::cast($request);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withQuery(QueryParameters $query): Builder
    {
        $this->queryParameters = ExtendedQueryParameters::cast($query);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function with($includePaths): Builder
    {
        $this->queryParameters = $this->queryParameters ?? new ExtendedQueryParameters();
        $this->queryParameters->setIncludePaths($includePaths);

        return $this;
    }

}
