<?php

namespace Webup\LaravelHelium\Core\Facades;

use Illuminate\Support\Facades\Facade;


class HeliumBreadcrumb extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'helium.breadcrumb';
    }
}
