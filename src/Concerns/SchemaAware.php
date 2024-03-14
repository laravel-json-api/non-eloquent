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
