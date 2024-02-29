# laravel-unique-slug-generator

```sh
composer require rajibbinalam/laravel-slug-generator
```
### update config/app.php

```php
"providers": [
    Rajib\LaravelSlugGenerator\SlugGeneratorServiceProvider::class,
],

"aliases": {
    'SlugGenerator' => Rajib\LaravelSlugGenerator\Facades\SlugGenerator::class,
}
```
### Publish Vendor:
```sh
php artisan vendor:publish
```
### Generating a unique slug
```php
use Rajib\LaravelSlugGenerator\Facades\SlugGenerator;

//SlugGenerator::generate($model, '$title', '$db_field_name');
SlugGenerator::generate(User::class, 'Hello World', 'slug');
```
 
### Separating a slug with '-'. Default Separated with '-'. It's changable. In config/SlugGenerator
```php
'separator' => '-',
```
### Also we can change the number of slugs make from same title. config/SlugGenerator
```php
'max_count' => '100',
```


### Example: Make Multiple slug from Same Title
```php
SlugGenerator::generate(User::class, 'Hello World', 'slug');  // output: hello-world

SlugGenerator::generate(User::class, 'Hello World', 'slug');  // output: hello-world-1

SlugGenerator::generate(User::class, 'Hello World', 'slug');  // output: hello-world-2

SlugGenerator::generate(User::class, 'Hello World', 'slug');  // output: hello-world-3
```
