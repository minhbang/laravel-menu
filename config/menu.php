<?php
return [
    /**
     * $patterns của các URI khi check active
     * Ví dụ: '/a/b' sẽ active khi truy cập /c/d*, /c/f*...
     * 'actives' => [
     *     '/a/b' => ['/c/d*', /c/f*],
     *     '/uri/khac' => '/co/the/dung/string/pattern'
     * ],
     */
    'actives'           => [],
    'add_route'         => true,
    'default_max_depth' => 2,
    'middleware'        => 'role:admin',
    // Danh sách menu presenters
    'presenters'        => [
        'main'  => Minhbang\Menu\Presenters\Main::class,
        'list1' => Minhbang\Menu\Presenters\List1::class,
        'list2' => Minhbang\Menu\Presenters\List2::class,
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
        'main'   => [
            'presenter' => 'main',
            'options'   => [
                'max_depth'  => 5,
                'attributes' => ['class' => 'nav navbar-nav'],
            ],
        ],
        'footer' => [
            'presenter' => 'list2',
            'options'   => [
                'max_depth'       => 2,
                'tag'             => '',
                'item_tag'        => 'div',
                'item_attributes' => ['class' => 'col-md-2 col-sm-6'],
            ],
        ],
        'bottom' => [
            'presenter' => 'list1',
            'options'   => [
                'max_depth'  => 1,
                'tag'        => 'ul',
                'item_tag'   => 'li',
                'attributes' => ['class' => 'pull-right list-inline'],
            ],
        ],
    ],
];