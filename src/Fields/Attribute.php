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

namespace LaravelJsonApi\NonEloquent\Fields;

use InvalidArgumentException;
use LaravelJsonApi\Contracts\Schema\Attribute as AttributeContract;
use LaravelJsonApi\Core\Schema\Concerns\Sortable;
use LaravelJsonApi\Core\Schema\Concerns\SparseField;

class Attribute implements AttributeContract
{

    use Sortable;
    use SparseField;

    /**
     * @var string
     */
    private string $name;

    /**
     * Create a new attribute field.
     *
     * @param mixed ...$arguments
     * @return static
     */
    public static function make(...$arguments): self
    {
        return new static(...$arguments);
    }

    /**
     * Attribute constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Expecting a non-empty field name.');
        }

        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return $this->name;
    }

}
