<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace LaravelJsonApi\NonEloquent\Capabilities;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LaravelJsonApi\Contracts\Query\QueryParameters;
use LaravelJsonApi\Contracts\Store\Builder;
use LaravelJsonApi\Core\Query\Custom\ExtendedQueryParameters;
use LaravelJsonApi\NonEloquent\Concerns\SchemaAware;
use LaravelJsonApi\NonEloquent\Concerns\ServerAware;

abstract class Capability implements Builder
{

    use ServerAware;
    use SchemaAware;

    /**
     * @var Request|null
     */
    protected ?Request $request = null;

    /**
     * @var ExtendedQueryParameters
     */
    protected ExtendedQueryParameters $queryParameters;

    /**
     * Capability constructor.
     */
    public function __construct()
    {
        $this->queryParameters = new ExtendedQueryParameters();
    }

    /**
     * Fluent constructor.
     *
     * @param mixed ...$arguments
     * @return static
     */
    public static function make(...$arguments): self
    {
        if (empty($arguments)) {
            return app(static::class);
        }

        return new static(...$arguments);
    }

    /**
     * @inheritDoc
     */
    public function withRequest(Request $request): Capability
    {
        $this->request = $request;
        $this->queryParameters = ExtendedQueryParameters::cast($request);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withQuery(QueryParameters $query): Capability
    {
        $this->queryParameters = ExtendedQueryParameters::cast($query);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function with($includePaths): Capability
    {
        $this->queryParameters->setIncludePaths($includePaths);

        return $this;
    }

    /**
     * Find to-one related resource using a JSON:API identifier.
     *
     * @param array|null $identifier
     * @return object|null
     */
    protected function toOne(?array $identifier): ?object
    {
        if ($identifier) {
            return $this->server()->store()->find(
                $identifier['type'],
                $identifier['id'],
            );
        }

        return null;
    }

    /**
     * Find to-many related resources using JSON:API identifiers.
     *
     * @param array $identifiers
     * @return array
     */
    protected function toMany(array $identifiers): array
    {
        return Collection::make(
            $this->server()->store()->findMany($identifiers),
        )->all();
    }

}
