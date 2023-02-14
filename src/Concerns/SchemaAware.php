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

namespace LaravelJsonApi\NonEloquent\Concerns;

use LaravelJsonApi\Contracts\Schema\Schema;

trait SchemaAware
{

    /**
     * @var Schema|null
     */
    protected ?Schema $schema = null;

    /**
     * Inject the schema.
     *
     * @param Schema $schema
     * @return $this
     */
    public function withSchema(Schema $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Inject the schema, if it is provided.
     *
     * @param Schema|null $schema
     * @return $this
     */
    public function maybeWithSchema(?Schema $schema): self
    {
        if ($schema) {
            $this->schema = $schema;
        }

        return $this;
    }

    /**
     * @return Schema
     */
    protected function schema(): Schema
    {
        if ($this->schema) {
            return $this->schema;
        }

        throw new \RuntimeException('No schema injected into repository class.');
    }
}
