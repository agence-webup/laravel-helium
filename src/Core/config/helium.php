<?php

return [
    "title" => "Administration",
    "main_title" => "Administration",

    "menu" => [
        "Dashboard" => [
            "current_route" => "admin.home",
            "icon" => "home",
            "url" => 'admin.home',
        ],
        "Admins" => [
            "current_route" => "admin.admin_user",
            "icon" => "users",
            "url" => 'admin.admin_user.index',
        ],
        // {{ Helium Crud Menu }}
    ],
    "shortcuts" => [],
    'modules' => [
        'contact' => [
            "enabled" => false,
        ],
        'setting' => [
            "enabled" => false,
        ],
        'redirection' => [
            "enabled" => false,
            'removeparts' => [
                'http://',
                'https://',
                config("app.url"),
                str_replace('http://', "", config("app.url")),
                str_replace('http://', "", config("app.url")),
            ],
        ],
    ],
];
