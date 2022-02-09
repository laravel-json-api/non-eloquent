<?php
/*
 * Copyright 2022 Cloud Creativity Limited
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
