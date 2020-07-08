<?php

return [
    "tab" => "Administration : Helium",
    "title" => "Administration",
    "links" => [
        "Aller sur le site" => "home",
    ],
    "menu" => [
        "Dashboard" => [
            "current-route" => "admin.home",
            "icon" => "home",
            "url" => 'admin.home',
        ],
    ],
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
            ]
        ],
    ]
];
