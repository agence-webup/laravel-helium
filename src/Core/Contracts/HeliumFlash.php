<?php

namespace Webup\LaravelHelium\Core\Contracts;


interface HeliumFlash
{
    /**
     * Add error Toaster
     *
     * @param string $message Message diplayed
     *
     * @return \Webup\LaravelHelium\Core\Classes\HeliumFlash
     */
    public function error(string $message);

    /**
     * Add success Toaster
     *
     * @param string $message Message diplayed
     *
     * @return \Webup\LaravelHelium\Core\Classes\HeliumFlash
     */
    public function success(string $message);

    /**
     * Add warning Toaster
     *
     * @param string $message Message diplayed
     *
     * @return \Webup\LaravelHelium\Core\Classes\HeliumFlash
     */
    public function warning(string $message);

    /**
     * Add info Toaster
     *
     * @param string $message Message diplayed
     *
     * @return \Webup\LaravelHelium\Core\Classes\HeliumFlash
     */
    public function info(string $message);

    /**
     * Generate toatser html
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|null
     */
    public function generate();
}
