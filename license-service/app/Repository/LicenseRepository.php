<?php

namespace App\Repository;

use App\Models\UserLicense;
use App\Repository\Contracts\CrudClass;

final class LicenseRepository extends CrudClass
{
    protected UserLicense $user;
    public function __construct()
    {
        $this->user = new UserLicense();

        parent::__construct($this->user);
    }
}