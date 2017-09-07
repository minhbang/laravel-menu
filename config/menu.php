<?php
return [
    // Các menus quản lý Menu
    'menus' => [
        'backend.sidebar.appearance.menu' => [
            'priority' => 3,
            'url' => 'route:backend.menu.index',
            'label' => 'trans:menu::common.menu',
            'icon' => 'fa-sitemap',
            'active' => 'backend/menu*',
        ],
    ],

    'default_max_depth' => 2,
    'middleware' => ['web', 'role:admin'],

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
            'title' => 'trans::menu::type.url.title',
            'icon' => 'external-link',
            'class' => \Minhbang\Menu\Types\UrlMenu::class,
        ],
        'route' => [
            'title' => 'trans::menu::type.route.title',
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
                        'label' => 'trans:backend.dashboard',
                        'icon' => 'dashboard',
                        'class' => 'special_link',
                    ],
                    'home' => [
                        'url' => '/',
                        'label' => 'trans:common.home',
                        'icon' => 'home',
                        'attributes' => ['target' => '_blank'],
                    ],
                    'content' => ['label' => 'trans:menu::common.items.content', 'icon' => 'fa-files-o'],
                    'user' => ['label' => 'trans:menu::common.items.user', 'icon' => 'fa-university'],
                    'setting' => ['label' => 'trans:menu::common.items.setting', 'icon' => 'fa-cogs'],
                    'maintenance' => ['label' => 'trans:menu::common.items.maintenance', 'icon' => 'fa-wrench'],
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
                        'label' => 'trans:backend.dashboard',
                        'icon' => 'dashboard',
                        'class' => 'special_link',
                    ],
                    'home' => [
                        'url' => '/',
                        'label' => 'trans:common.home',
                        'icon' => 'home',
                        'attributes' => ['target' => '_blank'],
                    ],
                    'content' => ['label' => 'trans:menu::common.items.content', 'icon' => 'fa-files-o'],
                    'media' => ['label' => 'trans:menu::common.items.media', 'icon' => 'fa-folder-open'],
                    'user' => ['label' => 'trans:menu::common.items.user', 'icon' => 'fa-university'],
                    'appearance' => ['label' => 'trans:menu::common.items.appearance', 'icon' => 'fa-paint-brush'],
                    'setting' => ['label' => 'trans:menu::common.items.setting', 'icon' => 'fa-cogs'],
                    'tools' => ['label' => 'trans:menu::common.items.tools', 'icon' => 'fa-wrench'],
                    'maintenance' => ['label' => 'trans:menu::common.items.maintenance', 'icon' => 'fa-wrench'],
                ],
                'presenter' => 'metis',
                'options' => [
                    'attributes' => ['id' => 'side-menu'],
                ],
            ],
        ],
    ],
];