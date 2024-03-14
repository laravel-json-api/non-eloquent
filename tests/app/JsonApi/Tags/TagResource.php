<?php
/*
 * Copyright 2024 Cloud Creativity Limited
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 */

declare(strict_types=1);

namespace App\JsonApi\Tags;

use Illuminate\Http\Request;
use LaravelJsonApi\Core\Resources\JsonApiResource;

class TagResource extends JsonApiResource
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
            'displayName' => $this->resource->getDisplayName(),
            'slug' => $this->resource->getSlug(),
        ];
    }

}
