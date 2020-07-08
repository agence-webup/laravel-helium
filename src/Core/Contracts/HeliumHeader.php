<?php

namespace Webup\LaravelHelium\Core\Contracts;


interface HeliumHeader
{
    public function title(string $title);
    public function save(string $label, string $route);
    public function add(string $label, string $route);
    public function contextual(array $contextualActions);
    public function generate();
}
