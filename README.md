# Laravel Menu

## Install

* **Thêm vào file composer.json của app**
```json
	"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/minhbang/laravel-menu"
        }
    ],
    "require": {
        "minhbang/laravel-menu": "dev-master"
    }
```
``` bash
$ composer update
```

* **Thêm vào file config/app.php => 'providers'**
```php
	Minhbang\LaravelMenu\MenuServiceProvider::class,
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
