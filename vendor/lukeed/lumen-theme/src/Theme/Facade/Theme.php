<?php
namespace Lukeed\Theme\Facade;

use Illuminate\Support\Facades\Facade;

class Theme extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Lukeed\Theme\Contracts\ThemeInterface';
    }
}
