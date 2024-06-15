<?php

namespace App\Repository;

use App\Models\UserLicense;
use App\Repository\Contracts\CrudClass;

final class LicenseRepository extends CrudClass
{
    public function __construct(protected UserLicense $user)
    {
        parent::__construct($this->user);
    }
}