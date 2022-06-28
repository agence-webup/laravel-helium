<?php

namespace Webup\LaravelHelium\Core\Facades;

use Illuminate\Support\Facades\Facade;


class HeliumHeader extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'helium.header';
    }
}
