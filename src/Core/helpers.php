<?php



if (!function_exists('helium_route_name')) {
    function helium_route_name($name)
    {
        return config("helium.admin.as") . $name;
    }
}


if (!function_exists('helium_route')) {
    function helium_route($name, $params = [], $absolute = true)
    {
        dd("test");
        return route(helium_route_name($name), $params, $absolute);
    }
}
