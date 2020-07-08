<?php

namespace Webup\LaravelHelium\Core\Contracts;


interface HeliumBreadcrumb
{
    public function push(string $label, string $route);
    public function generate();
}
