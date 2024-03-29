<?php

return [
    "admin" => [
        "prefix" => "admin",
        "as" => "admin.",
    ],

    "title" => "Administration",
    "main_title" => "Administration",

    "menu" => [
        "Dashboard" => [
            "current_route" => "home",
            "icon" => "home",
            "url" => "home",
        ],
        // {{ Helium Crud Menu }}
        "Admins" => [
            "icon" => "users",
            "current_route" => [
                "admin_user",
                "role"
            ],
            'links' => [
                "Utilisateurs" => [
                    "url" => "admin_user.index",
                    "permissions" => "admin_users.read",
                ],
                "Rôles" => [
                    "url" => "role.index",
                    "permissions" => "roles.read",
                ],
            ]
        ],
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
