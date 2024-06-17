<?php

namespace App\Repository;

use App\Models\UserBucket;
use App\Repository\Contracts\CrudClass;

final class UserBucketRepository extends CrudClass
{
    public function __construct(protected UserBucket $bucket)
    {
        parent::__construct($this->$bucket);
    }
}