<?php
namespace Lukeed\Theme;

use Illuminate\Support\ServiceProvider;
use Lukeed\Theme\Commands\ThemeListCommand;
use Lukeed\Theme\Commands\ThemeMakeCommand;
use Lukeed\Theme\Contracts\ThemeInterface;
use Lukeed\Theme\Theme;

class ThemeServiceProvider extends ServiceProvider
{
    public function register()
    {
        require_once __DIR__ . '/helpers.php';
        $this->registerCore();
    }

    public function registerCore()
    {
        $this->app->bind(ThemeInterface::class, Theme::class);

        $this->commands(ThemeListCommand::class);
        $this->commands(ThemeMakeCommand::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['theme'];
    }
}
