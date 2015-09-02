<?php
return [
    'types'     => Minhbang\LaravelMenu\MenuType::class,
    /**
     * $patterns của các URI khi check active
     * Ví dụ: '/a/b' sẽ active khi truy cập /c/d*, /c/f*...
     * 'actives' => [
     *     '/a/b' => ['/c/d*', /c/f*],
     *     '/uri/khac' => '/co/the/dung/string/pattern'
     * ],
     */
    'actives'   => [],
    'add_route' => true,
    'max_depth' => 3,
    'middlewares' => 'admin'
];