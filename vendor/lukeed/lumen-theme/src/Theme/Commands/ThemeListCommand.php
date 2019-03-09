<?php
namespace Lukeed\Theme\Commands;

use Illuminate\Console\Command;
use Lukeed\Theme\Theme;

class ThemeListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all available themes';

    /**
     * Theme service.
     *
     * @var Theme
     */
    protected $theme;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $themes = $this->laravel['theme']->all();
        $headers = ['Name', 'Author', 'Directory'];

        $output = [];
        foreach ($themes as $theme) {
            $output[] = [
                'Name' => $theme->getName(),
                'Author' => $theme->getAuthor(),
                'Directory' => $theme->getDirectory(),
            ];
        }

        $this->table($headers, $output);
    }
}
