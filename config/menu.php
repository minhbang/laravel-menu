<?php
return [
    'config'      => Minhbang\LaravelMenu\MenuConfig::class,
    /**
     * $patterns của các URI khi check active
     * Ví dụ: '/a/b' sẽ active khi truy cập /c/d*, /c/f*...
     * 'actives' => [
     *     '/a/b' => ['/c/d*', /c/f*],
     *     '/uri/khac' => '/co/the/dung/string/pattern'
     * ],
     */
    'actives'     => [],
    'add_route'   => true,
    'default_max_depth'   => 2,
    'middlewares' => 'admin'
];