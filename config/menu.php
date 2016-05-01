<?php
return [
    // Các menus quản lý Menu
    'menus' => [
        'backend.sidebar.setting.menu' => [
            'priority' => 2,
            'url'      => 'route:backend.menu.index',
            'label'    => 'trans:menu::common.menu',
            'icon'     => 'fa-sitemap',
            'active'   => 'backend/menu*',
        ],
    ],

    'add_route'         => true,
    'default_max_depth' => 2,
    'middleware'        => 'role:admin',

    /**
     * $patterns của các URI khi check active
     * Ví dụ: '/a/b' sẽ active khi truy cập /c/d*, /c/f*...
     * 'actives' => [
     *     '/a/b' => ['/c/d*', /c/f*],
     *     '/uri/khac' => '/co/the/dung/string/pattern'
     * ],
     */
    'actives'           => [],

    // Danh sách menu presenters
    'presenters'        => [
        'main'  => Minhbang\Menu\Presenters\Main::class,
        'list1' => Minhbang\Menu\Presenters\List1::class,
        'list2' => Minhbang\Menu\Presenters\List2::class,
        'metis' => Minhbang\Menu\Presenters\Metis::class,
    ],

    // Danh sách menu item types
    'types'             => [
        'url'   => Minhbang\Menu\Types\Url::class,
        'route' => Minhbang\Menu\Types\Route::class,
    ],

    /**
     * Cấu hình danh sách Menus
     */
    'settings'          => [
        // Menu zone cho frontend
        'frontend' => [
            'main'   => [
                'editable'  => true,
                'presenter' => 'main',
                'options'   => [
                    'max_depth'  => 5,
                    'attributes' => ['class' => 'nav navbar-nav'],
                ],
            ],
            'footer' => [
                'editable'  => true,
                'presenter' => 'list2',
                'options'   => [
                    'max_depth'       => 2,
                    'tag'             => '',
                    'item_tag'        => 'div',
                    'item_attributes' => ['class' => 'col-md-2 col-sm-6'],
                ],
            ],
            'bottom' => [
                'editable'  => true,
                'presenter' => 'list1',
                'options'   => [
                    'max_depth'  => 1,
                    'tag'        => 'ul',
                    'item_tag'   => 'li',
                    'attributes' => ['class' => 'pull-right list-inline'],
                ],
            ],
        ],
        // Menus zone cá nhân của user
        'my'       => [
            'sidebar' => [
                'items'     => [
                    [
                        'url'        => '/',
                        'label'      => 'trans:common.home',
                        'icon'       => 'home',
                        'attributes' => ['target' => '_blank'],
                    ],
                    'content'     => ['label' => 'trans:menu::common.items.content', 'icon' => 'fa-files-o'],
                    'user'        => ['label' => 'trans:menu::common.items.user', 'icon' => 'fa-university'],
                    'setting'     => ['label' => 'trans:menu::common.items.setting', 'icon' => 'fa-cogs'],
                    'maintenance' => ['label' => 'trans:menu::common.items.maintenance', 'icon' => 'fa-wrench'],
                ],
                'presenter' => 'metis',
                'options'   => [
                    'attributes' => ['id' => 'side-menu'],
                ],
            ],
        ],
        // Menu zone quản trị
        'backend'  => [
            'sidebar' => [
                'items'     => [
                    'dashboard'   => [
                        'url'   => 'route:backend.dashboard',
                        'label' => 'trans:backend.dashboard',
                        'icon'  => 'dashboard',
                        'class' => 'special_link',
                    ],
                    'home'        => [
                        'url'        => '/',
                        'label'      => 'trans:common.home',
                        'icon'       => 'home',
                        'attributes' => ['target' => '_blank'],
                    ],
                    'content'     => ['label' => 'trans:menu::common.items.content', 'icon' => 'fa-files-o'],
                    'user'        => ['label' => 'trans:menu::common.items.user', 'icon' => 'fa-university'],
                    'setting'     => ['label' => 'trans:menu::common.items.setting', 'icon' => 'fa-cogs'],
                    'maintenance' => ['label' => 'trans:menu::common.items.maintenance', 'icon' => 'fa-wrench'],
                ],
                'presenter' => 'metis',
                'options'   => [
                    'attributes' => ['id' => 'side-menu'],
                ],
            ],
        ],
    ],
];