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

namespace LaravelJsonApi\NonEloquent\Fields;

use InvalidArgumentException;
use LaravelJsonApi\Contracts\Schema\Relation as RelationContract;
use LaravelJsonApi\Core\Schema\Concerns\EagerLoadable;
use LaravelJsonApi\Core\Schema\Concerns\Filterable;
use LaravelJsonApi\Core\Schema\Concerns\RequiredForValidation;
use LaravelJsonApi\Core\Schema\Concerns\SparseField;
use LaravelJsonApi\Core\Support\Str;

abstract class Relation implements RelationContract
{

    use EagerLoadable;
    use Filterable;
    use RequiredForValidation;
    use SparseField;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string|null
     */
    private ?string $inverse = null;

    /**
     * @var array|null
     */
    private ?array $allInverse = null;

    /**
     * @var string|null
     */
    private ?string $uriName = null;

    /**
     * Guess the inverse resource type.
     *
     * @return string
     */
    abstract protected function guessInverse(): string;

    /**
     * Create a new relation.
     *
     * @param mixed ...$arguments
     * @return static
     */
    public static function make(...$arguments): self
    {
        return new static(...$arguments);
    }

    /**
     * Relation constructor.
     *
     * @param string $fieldName
     */
    public function __construct(string $fieldName)
    {
        $this->name = $fieldName;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Use the field-name as-is for relationship URLs.
     *
     * @return $this
     */
    public function retainFieldName(): self
    {
        $this->uriName = $this->name();

        return $this;
    }

    /**
     * Use the provided string as the URI fragment for the field name.
     *
     * @param string $uri
     * @return $this
     */
    public function withUriFieldName(string $uri): self
    {
        if (empty($uri)) {
            throw new InvalidArgumentException('Expecting a non-empty string URI fragment.');
        }

        $this->uriName = $uri;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function uriName(): string
    {
        if ($this->uriName) {
            return $this->uriName;
        }

        return $this->uriName = $this->guessUriName();
    }

    /**
     * @inheritDoc
     */
    public function toMany(): bool
    {
        return false === $this->toOne();
    }

    /**
     * Set the inverse resource type.
     *
     * @param string $resourceType
     * @return $this
     */
    public function type(string $resourceType): self
    {
        if (empty($resourceType)) {
            throw new InvalidArgumentException('Expecting a non-empty string.');
        }

        $this->inverse = $resourceType;

        return $this;
    }

    /**
     * Set the inverse resource types (for a polymorphic relation).
     *
     * @param string ...$resourceTypes
     * @return $this
     */
    public function types(string ...$resourceTypes): self
    {
        if (2 > count($resourceTypes)) {
            throw new \InvalidArgumentException('Expecting at least two inverse resource types.');
        }

        $this->allInverse = $resourceTypes;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function inverse(): string
    {
        if ($this->inverse) {
            return $this->inverse;
        }

        return $this->inverse = $this->guessInverse();
    }

    /**
     * @inheritDoc
     */
    public function allInverse(): array
    {
        if (is_null($this->allInverse)) {
            return [$this->inverse()];
        }

        return $this->allInverse;
    }

    /**
     * Guess the field name as it appears in a URI.
     *
     * @return string
     */
    private function guessUriName(): string
    {
        return Str::dasherize($this->name());
    }

}
