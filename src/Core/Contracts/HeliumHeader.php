<?php

namespace Webup\LaravelHelium\Core\Contracts;


interface HeliumHeader
{
    /**
     * Set title
     *
     * @param string $title Header's title
     *
     * @return \Webup\LaravelHelium\Core\Classes\HeliumHeader
     */
    public function title(string $title);

    /**
     * Set save action
     *
     * @param string $label Save button label
     * @param string $formId Id of saving form
     *
     * @return \Webup\LaravelHelium\Core\Classes\HeliumHeader
     */
    public function save(string $label, string $formId);

    /**
     * Set add action
     *
     * @param string $label Add button label
     * @param string $route Either route name or full url
     *
     * @return \Webup\LaravelHelium\Core\Classes\HeliumHeader
     */
    public function add(string $label, string $route);

    /**
     * Set custom action
     *
     * @param string $label Custom button label
     * @param array $attrs html attributes
     * @param string $icon Feather icon name
     *
     * @return \Webup\LaravelHelium\Core\Classes\HeliumHeader
     */
    public function custom(string $label, array $attrs = [], string $icon = null);

    /**
     * Set custom action
     *
     * @param string $html Html of element
     *
     * @return \Webup\LaravelHelium\Core\Classes\HeliumHeader
     */
    public function pushCustom(string $html);

    /**
     * Set contextual actions
     *
     * Example usage:
     * Helium::header()->contextual([
     *      "Action label" => [
     *          "route" => "https//mydomain.com",
     *          "dangerous" => true,
     *          "data-attr" => "foo"
     *      ]
     * ]);
     *
     * @param array $actions Contextual actions

     * @return \Webup\LaravelHelium\Core\Classes\HeliumHeader
     */
    public function contextual(array $contextualActions);

    /**
     * Generate header html
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function generate();
}
