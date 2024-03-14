<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace LaravelJsonApi\NonEloquent\Concerns;

trait HasModelResourceIdAndFieldName
{

    use HasModelOrResourceId;

    /**
     * @var string
     */
    protected string $fieldName;

    /**
     * Set the relation field name.
     *
     * @param string $fieldName
     * @return $this
     */
    public function withFieldName(string $fieldName): self
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * Get the relation's value by retrieving it from the JSON:API resource class.
     *
     * @return mixed
     */
    protected function value()
    {
        $resource = $this->server()->resources()->create(
            $this->modelOrFail()
        );

        return $resource->relationship($this->fieldName)->data();
    }
}
