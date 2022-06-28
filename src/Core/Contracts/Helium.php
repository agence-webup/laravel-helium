<?php

namespace Webup\LaravelHelium\Core\Contracts;


interface Helium
{
    /**
     * @return \Webup\LaravelHelium\Core\Contracts\HeliumBreadcrumb
     */
    public function breadcrumb();

    /**
     * @return \Webup\LaravelHelium\Core\Contracts\HeliumHeader
     */
    public function header();

    /**
     * @return \Webup\LaravelHelium\Core\Contracts\HeliumFlash
     */
    public function flash();
}
