<?php

namespace Rajib\LaravelSlugGenerator\Facades;

use Illuminate\Support\Facades\Facade;
use Rajib\LaravelSlugGenerator\SlugGenerator as LaravelSlugGeneratorSlugGenerator;

/**
 * @see LaravelSlugGeneratorSlugGenerator;
 */
class SlugGenerator extends Facade
{


    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'Laravel-Slug-Generator';
    }
}
