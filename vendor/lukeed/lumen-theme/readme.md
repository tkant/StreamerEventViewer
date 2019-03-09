# Lumen Theme

[![Build Status](https://travis-ci.org/lukeed/lumen-theme.svg?branch=master)](https://travis-ci.org/lukeed/lumen-theme)
[![Latest Stable Version](https://poser.pugx.org/lukeed/lumen-theme/v/stable)](https://packagist.org/packages/lukeed/lumen-theme)
[![License](https://poser.pugx.org/lukeed/lumen-theme/license)](https://packagist.org/packages/lukeed/lumen-theme)

Add theming support to your Lumen 5.* projects.

For Laravel support, go to [Laravel Theme](https://github.com/karlomikus/theme).

### Features

- Custom theme locations
- Support for theme inheritence with theme fallback
- Theme assets loading
- Artisan console commands

## Install

Require it via terminal like so:
``` bash
$ composer require lukeed/lumen-theme
```

Or add the package to your composer file:

``` json
"lukeed/lumen-theme": "1.*"
```

Add a new service provider and optional facade to your `bootstrap/app.php` file:

```php
// Service provider
$app->register(Lukeed\Theme\ThemeServiceProvider::class);

// Facade
class_alias(Lukeed\Theme\Facade\Theme::class, 'Theme');
```

## Configuration

#### theme.path

> Type: string

> Default: `public/themes`

The path to the `themes` directory, where all themes should live.

To change this value, you may either create a custom `config/theme.php` file and load it inside `bootstrap/app.php` or you may set the value directly via the `config()` helper anywhere inside your application.

```php
// bootstrap/app.php
config(['theme.path' => realpath(base_path('public/custom/path/themes'))]);
```

OR

```php
// config/theme.php
return [
    'path' => realpath(base_path('public/custom/path/themes'))])
];

// bootstrap/app.php
$app->configure('theme');
```

## Creating a Theme

Every theme directory **must** contain a `views` folder and a `theme.json` file, which contains descriptive information about the theme.

```json
{
    "name": "Theme name",
    "author": "Author Name",
    "description": "Default theme description",
    "version": "1.0",
    "directory": "theme-folder",
    "parent": null
}
```

The `name`, `author` and `directory` fields are **required**.

The `directory` value must match the name of the theme directory; eg: `public/themes/theme-folder`.

If your theme is meant to extend or inherit another theme's views, include the `directory` name "parent theme" as the `parent` value; eg: `parent-folder`.

## View Hierarchy

Given the example:
```php
view('home');
```

The currently active theme will be scanned for a `home.blade.php`. 

If there is a `parent` attached to the theme, its directory will be scanned next.

Lastly, Lumen's `view.paths` config value will searched. By default, this is `resources/views`.

## Usage

The `theme.path` directory will be scanned for all available themes. 

**If only one theme is found, it will automatically be selected as the active theme.** To manually select a different theme, you may use the `set` method which accepts a `directory` value.

```php
Theme::set('theme-folder');
```

Then you call views like you usually do in laravel:

``` php
view('home', []);
```

This will firstly check if there is a home.blade.php in current theme directory.
If none is found then it checks parent theme, and finally falls back to default laravel views location.

You can also inject theme instance using ThemeInterface.

``` php
use lukeed\Theme\Contracts\ThemeInterface;

private $theme;

public function __construct(ThemeInterface $theme)
{
    $this->theme = $theme
}
```

## Available methods

Here's the list of methods you can access:

``` php
// Activate/set theme
Theme::set('theme-namespace');

// Get all available themes as an array
Theme::all();

// Get currently active
Theme::get();

// Get theme by namespace
Theme::get('specific-namespace');

// Override default theme path
Theme::setDefaultThemePath('new/path/to/themes');

// Check if theme exists
Theme::has('theme-namespace');

// Render theme path URL
theme_url('assets/style.css');
```

### Artisan commands

Get a table of all found themes:
``` bash
$ php artisan theme:list

+------------------+-------------+------------+
| Name             | Author      | Namespace  |
+------------------+-------------+------------+
| Bootstrap theme  | Karlo Mikus | bootstrap  |
| Default theme    | Test Author | default    |
| Foundation theme | Lorem Ipsum | foundation |
| Test theme       | Dolor Sitha | test       |
+------------------+-------------+------------+
```

Create a theme directory with config file:
``` bash
$ php artisan theme:make

 Template name:
 > Theme name

 Template author:
 > Firstn Lastn

Theme created succesfully!
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## TODO

- Contact me for ideas
