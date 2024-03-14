<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace App\JsonApi\Sites;

use Illuminate\Http\Request;
use LaravelJsonApi\Core\Resources\JsonApiResource;

class SiteResource extends JsonApiResource
{

    /**
     * Get the resource id.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->resource->getSlug();
    }

    /**
     * Get the resource's attributes.
     *
     * @param Request|null $request
     * @return iterable
     */
    public function attributes($request): iterable
    {
        return [
            'domain' => $this->resource->getDomain(),
            'name' => $this->resource->getName(),
        ];
    }

    /**
     * Get the resource's relationships.
     *
     * @param Request|null $request
     * @return iterable
     */
    public function relationships($request): iterable
    {
        return [
            $this->relation('owner')->withData($this->resource->getOwner()),
            $this->relation('tags')->withData($this->resource->getTags()),
        ];
    }
}
