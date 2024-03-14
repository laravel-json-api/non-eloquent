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
