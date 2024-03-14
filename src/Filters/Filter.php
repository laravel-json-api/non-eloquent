<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace LaravelJsonApi\NonEloquent\Filters;

use LaravelJsonApi\Contracts\Schema\Filter as FilterContract;

class Filter implements FilterContract
{

    /**
     * @var string
     */
    private string $key;

    /**
     * Create a new filter.
     *
     * @param mixed ...$args
     * @return static
     */
    public static function make(...$args): self
    {
        return new static(...$args);
    }

    /**
     * Filter constructor.
     *
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @inheritDoc
     */
    public function key(): string
    {
        return $this->key;
    }

}
