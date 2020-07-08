<?php

namespace Webup\LaravelHelium\Core\Classes;

use Webup\LaravelHelium\Core\Contracts\Helium as HeliumContract;
use Webup\LaravelHelium\Core\Helpers\HeliumHelper;

class Helium implements HeliumContract
{
    /**
     * {@inheritdoc}
     */
    public function breadcrumb()
    {
        return app('helium.breadcrumb');
    }

    /**
     * {@inheritdoc}
     */
    public function header()
    {
        return app('helium.header');
    }
}
