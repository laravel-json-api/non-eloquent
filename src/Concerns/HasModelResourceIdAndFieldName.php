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
