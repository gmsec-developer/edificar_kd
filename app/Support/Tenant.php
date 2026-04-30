<?php

namespace App\Support;

class Tenant
{
    public static function companyId(): ?int
    {
        return auth()->check() ? auth()->user()->company_id : null;
    }

    public static function company()
    {
        return auth()->check() ? auth()->user()->company : null;
    }
}
