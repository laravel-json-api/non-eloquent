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

use LaravelJsonApi\Core\Support\Str;

class ToOne extends Relation
{
    /**
     * @inheritDoc
     */
    public function toOne(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function guessInverse(): string
    {
        return Str::dasherize(
            Str::plural($this->name())
        );
    }

}
