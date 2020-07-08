<?php

namespace Webup\LaravelHelium\Core\Contracts;


interface HeliumBreadcrumb
{
    /**
     * Push new item to current breadcrumb
     *
     * @param string $label Menu's label
     * @param string $route Either route name or full url
     *
     * @return \Webup\LaravelHelium\Core\Classes\HeliumBreadcrumb
     */
    public function push(string $label, string $route);

    /**
     * Generate breacrumb html
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|null
     */
    public function generate();
}
