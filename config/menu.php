<?php
return [
    // Các menus quản lý Menu
    'menus' => [
        'backend.sidebar.appearance.menu' => [
            'priority' => 3,
            'url' => 'route:backend.menu.index',
            'label' => '__:Menu',
            'icon' => 'fa-sitemap',
            'active' => 'backend/menu*',
        ],
    ],

    'default_max_depth' => 2,
    'middleware' => ['web', 'role:sys.admin'],

    /**
     * $patterns của các URI khi check active
     * Ví dụ: '/a/b' sẽ active khi truy cập /c/d*, /c/f*...
     * 'actives' => [
     *     '/a/b' => ['/c/d*', /c/f*],
     *     '/uri/khac' => '/co/the/dung/string/pattern'
     * ],
     */
    'actives' => [],

    // Danh sách menu presenters
    'presenters' => [
        'main' => \Minhbang\Menu\Presenters\Main::class,
        'list1' => \Minhbang\Menu\Presenters\List1::class,
        'list2' => \Minhbang\Menu\Presenters\List2::class,
        'metis' => \Minhbang\Menu\Presenters\Metis::class,
    ],

    // Danh sách menu item types
    'types' => [
        'url' => [
            'title' => '__::Url',
            'icon' => 'external-link',
            'class' => \Minhbang\Menu\Types\UrlMenu::class,
        ],
        'route' => [
            'title' => '__::Route',
            'icon' => 'link',
            'class' => \Minhbang\Menu\Types\RouteMenu::class,
        ],
    ],

    /**
     * Cấu hình danh sách Menus
     */
    'settings' => [
        // Menu zone cho frontend
        'frontend' => [
            'main' => [
                'editable' => true,
                'presenter' => 'main',
                'options' => [
                    'max_depth' => 5,
                    'attributes' => ['class' => 'nav navbar-nav'],
                ],
            ],
            'footer' => [
                'editable' => true,
                'presenter' => 'list2',
                'options' => [
                    'max_depth' => 2,
                    'tag' => '',
                    'item_tag' => 'div',
                    'item_attributes' => ['class' => 'col-md-2 col-sm-6'],
                ],
            ],
            'bottom' => [
                'editable' => true,
                'presenter' => 'list1',
                'options' => [
                    'max_depth' => 1,
                    'tag' => 'ul',
                    'item_tag' => 'li',
                    'attributes' => ['class' => 'pull-right list-inline'],
                ],
            ],
        ],
        // Menus zone của user
        'manage' => [
            'sidebar' => [
                'items' => [
                    'dashboard' => [
                        'url' => 'route:manage.dashboard',
                        'label' => '__:Dashboard',
                        'icon' => 'dashboard',
                        'class' => 'special_link',
                    ],
                    'home' => [
                        'url' => '/',
                        'label' => '__:Homepage',
                        'icon' => 'home',
                        'attributes' => ['target' => '_blank'],
                    ],
                    'content' => ['label' => '__:Content', 'icon' => 'fa-files-o'],
                    'user' => ['label' => '__:User', 'icon' => 'fa-university'],
                    'setting' => ['label' => '__:Setting', 'icon' => 'fa-cogs'],
                    'maintenance' => ['label' => '__:Maintenance', 'icon' => 'fa-wrench'],
                ],
                'presenter' => 'metis',
                'options' => [
                    'attributes' => ['id' => 'side-menu'],
                ],
            ],
        ],
        // Menu zone quản trị
        'backend' => [
            'sidebar' => [
                'items' => [
                    'dashboard' => [
                        'url' => 'route:backend.dashboard',
                        'label' => '__:Dashboard',
                        'icon' => 'dashboard',
                        'class' => 'special_link',
                    ],
                    'home' => [
                        'url' => '/',
                        'label' => '__:Homepage',
                        'icon' => 'home',
                        'attributes' => ['target' => '_blank'],
                    ],
                    'content' => ['label' => '__:Content', 'icon' => 'fa-files-o'],
                    'media' => [
                        'label' => '__:Media',
                        'icon' => 'fa-folder-open',
                        'role' => 'sys.admin',
                    ],
                    'user' => [
                        'label' => '__:User',
                        'icon' => 'fa-university',
                        'role' => 'sys.admin',
                    ],
                    'appearance' => [
                        'label' => '__:Appearance',
                        'icon' => 'fa-paint-brush',
                        'role' => 'sys.admin',
                    ],
                    'setting' => [
                        'label' => '__:Setting',
                        'icon' => 'fa-cogs',
                        'role' => 'sys.admin',
                    ],
                    'tools' => [
                        'label' => '__:Tools',
                        'icon' => 'fa-wrench',
                        'role' => 'sys.admin',
                    ],
                    'maintenance' => [
                        'label' => '__:Maintenance',
                        'icon' => 'fa-wrench',
                        'role' => 'sys.admin',
                    ],
                ],
                'presenter' => 'metis',
                'options' => [
                    'attributes' => ['id' => 'side-menu'],
                ],
            ],
        ],
    ],
];