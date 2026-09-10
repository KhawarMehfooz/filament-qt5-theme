<?php

namespace Khwr\FilamentQt5Theme;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;

class FilamentQt5ThemePlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-qt5-theme';
    }

    public function register(Panel $panel): void
    {
        FilamentAsset::register([
            Css::make('filament-qt5-theme-styles', __DIR__ . '/../resources/dist/theme.css'),
        ], package: 'khwr/filament-qt5-theme');
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
