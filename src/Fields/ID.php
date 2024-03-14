<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
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
