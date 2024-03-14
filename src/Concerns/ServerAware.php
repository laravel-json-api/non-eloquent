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

use LaravelJsonApi\Contracts\Server\Server;

trait ServerAware
{

    /**
     * @var Server|null
     */
    protected ?Server $server = null;

    /**
     * Inject the server.
     *
     * @param Server $server
     * @return $this
     */
    public function withServer(Server $server): self
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Inject the server, if it is provided.
     *
     * @param Server|null $server
     * @return $this
     */
    public function maybeWithServer(?Server $server): self
    {
        if ($server) {
            $this->server = $server;
        }

        return $this;
    }

    /**
     * @return Server
     */
    protected function server(): Server
    {
        if ($this->server) {
            return $this->server;
        }

        throw new \RuntimeException('No server injected into repository class.');
    }
}
