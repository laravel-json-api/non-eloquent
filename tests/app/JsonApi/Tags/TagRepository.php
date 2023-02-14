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

namespace App\JsonApi\Tags;

use App\Entities\TagStorage;
use LaravelJsonApi\NonEloquent\AbstractRepository;

class TagRepository extends AbstractRepository
{

    /**
     * @var TagStorage
     */
    private TagStorage $storage;

    /**
     * TagRepository constructor.
     *
     * @param TagStorage $storage
     */
    public function __construct(TagStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public function find(string $resourceId): ?object
    {
        return $this->storage->find($resourceId);
    }

}
