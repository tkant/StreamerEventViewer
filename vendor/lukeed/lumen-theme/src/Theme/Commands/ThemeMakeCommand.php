<?php
namespace Lukeed\Theme\Commands;

use Illuminate\Console\Command;
use Lukeed\Theme\Theme;
use Illuminate\Filesystem\Filesystem as File;

class ThemeMakeCommand extends COmmand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:make {directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new theme folder';

    /**
     * Theme service.
     *
     * @var Theme
     */
    protected $theme;

    /**
     * Theme directory
     *
     * @var string
     */
    private $themeDir;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $themeDir = $this->argument('directory');
        $themeDir = strtolower($themeDir);

        $this->themeDir = $themeDir;

        $name = $this->ask('Template name');
        $author = $this->ask('Template author');

        $this->makeThemeFolder();
        $this->makeThemeConfig($name, $author);

        $this->info('Theme created succesfully!');
    }

    private function makeThemeFolder()
    {
        $file = new File;
        $location = config('theme.path');
        $path = $location . DIRECTORY_SEPARATOR . $this->themeDir;

        if (!$file->isDirectory($path)) {
            $file->makeDirectory($path);
            $file->makeDirectory($path . DIRECTORY_SEPARATOR . 'views');
        } else {
            throw new \Exception('Theme already exists!');
        }
    }

    private function makeThemeConfig($name, $author)
    {
        $file = new File;
        $location = config('theme.path');
        $path = $location . DIRECTORY_SEPARATOR . $this->themeDir . '/theme.json';

        if (!$file->exists($path)) {
            $stub = file_get_contents(__DIR__ . '/stubs/theme.stub');
            $stub = str_replace('$NAME$', $name, $stub);
            $stub = str_replace('$AUTHOR$', $author, $stub);
            $stub = str_replace('$DIRECTORY$', $this->themeDir, $stub);

            $file->put($path, $stub);
        } else {
            throw new \Exception('Theme config file already exists!');
        }
    }
}
