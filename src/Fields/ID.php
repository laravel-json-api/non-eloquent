<?php
/*
 * Copyright 2022 Cloud Creativity Limited
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

use LaravelJsonApi\Contracts\Schema\ID as IdContract;
use LaravelJsonApi\Core\Schema\Concerns\ClientIds;
use LaravelJsonApi\Core\Schema\Concerns\MatchesIds;
use LaravelJsonApi\Core\Schema\Concerns\Sortable;

class ID implements IdContract
{

    use ClientIds;
    use MatchesIds;
    use Sortable;

    /**
     * Create a new ID field.
     *
     * @param mixed ...$args
     * @return static
     */
    public static function make(...$args): self
    {
        return new static(...$args);
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'id';
    }

    /**
     * @inheritDoc
     */
    public function isSparseField(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function key(): ?string
    {
        return null;
    }

}
