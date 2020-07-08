<?php

namespace Webup\LaravelHelium\Core\Classes;

use Webup\LaravelHelium\Core\Contracts\Helium as HeliumContract;

/**
 * Helium helper
 *
 * Share Helium specifics helpers and make it easier to use
 */
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
