<?php

namespace Hotmeteor\WritingStyle;

use Illuminate\Support\Facades\Facade as LaravelFacade;

/**
 * @method static string apa(string $value)
 */
class Facade extends LaravelFacade
{
    protected static function getFacadeAccessor()
    {
        return TitleFactory::class;
    }
}
