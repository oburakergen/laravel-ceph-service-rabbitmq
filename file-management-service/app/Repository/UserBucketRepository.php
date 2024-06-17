<?php

namespace App\Repository;

use App\Models\UserBucket;
use App\Repository\Contracts\CrudClass;

final class UserBucketRepository extends CrudClass
{
    protected UserBucket $bucket;
    public function __construct()
    {
        $this->bucket = new UserBucket();
        parent::__construct($this->bucket);
    }
}