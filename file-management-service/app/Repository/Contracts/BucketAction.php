<?php

namespace App\Repository\Contracts;

enum BucketAction: string
{
    case createUser = 'createUser';
    case createBucket = 'createBucket';
    case deleteBucket = 'deleteBucket';
}