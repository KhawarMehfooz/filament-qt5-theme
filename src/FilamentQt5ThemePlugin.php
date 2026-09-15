<?php

namespace Khwr\FilamentQt5Theme;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Khwr\FilamentQt5Theme\Support\ThemeConfigResolver;

class FilamentQt5ThemePlugin implements Plugin
{
    private const GOOGLE_FONTS_STYLESHEET_URL = 'https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=IBM+Plex+Sans:wght@400;500;600&display=swap';

    private ?string $accentColor = null;

    private ?string $fontSans = null;

    private ?string $fontMono = null;

    private bool $googleFonts = true;

    private ?string $density = null;

    private ?string $radius = null;

    private ?string $successColor = null;

    private ?string $errorColor = null;

    private ?string $warningColor = null;

    private ?string $neutralColor = null;

    /**
     * @var array<string, string>
     */
    private array $surface = [];

    public function getId(): string
    {
        return 'filament-qt5-theme';
    }

    public function accentColor(string $color): static
    {
        $this->accentColor = $color;

        return $this;
    }

    public function typography(?string $sans = null, ?string $mono = null, bool $googleFonts = true): static
    {
        $this->fontSans = $sans;
        $this->fontMono = $mono;
        $this->googleFonts = $googleFonts;

        return $this;
    }

    public function density(string $density): static
    {
        $this->density = $density;

        return $this;
    }

    public function radius(string $radius): static
    {
        $this->radius = $radius;

        return $this;
    }

    public function hasGoogleFontsEnabled(): bool
    {
        return $this->googleFonts;
    }

    public function semanticColors(?string $success = null, ?string $error = null, ?string $warning = null): static
    {
        $this->successColor = $success;
        $this->errorColor = $error;
        $this->warningColor = $warning;

        return $this;
    }

    public function neutralColor(string $color): static
    {
        $this->neutralColor = $color;

        return $this;
    }

    /**
     * @param  array<string, string>  $surface
     */
    public function surface(array $surface): static
    {
        $this->surface = $surface;

        return $this;
    }

    public function register(Panel $panel): void
    {
        FilamentAsset::register([
            Css::make('filament-qt5-theme-styles', __DIR__ . '/../resources/dist/theme.css'),
        ], package: 'khwr/filament-qt5-theme');

        $variables = ThemeConfigResolver::resolve([
            'accentColor' => $this->accentColor,
            'typography' => [
                'sans' => $this->fontSans,
                'mono' => $this->fontMono,
            ],
            'density' => $this->density,
            'radius' => $this->radius,
            'semanticColors' => [
                'success' => $this->successColor,
                'error' => $this->errorColor,
                'warning' => $this->warningColor,
            ],
            'neutralColor' => $this->neutralColor,
            'surface' => $this->surface,
        ]);

        if ($variables !== []) {
            FilamentAsset::registerCssVariables($variables, package: 'khwr/filament-qt5-theme');
        }

        $darkVariables = ThemeConfigResolver::resolveDark([
            'accentColor' => $this->accentColor,
            'neutralColor' => $this->neutralColor,
        ]);

        if ($darkVariables !== []) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => ThemeConfigResolver::toStyleBlock(':root.dark', $darkVariables),
            );
        }

        if ($this->googleFonts) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => '<link rel="stylesheet" href="' . self::GOOGLE_FONTS_STYLESHEET_URL . '" />',
            );
        }
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
