<?php

namespace Khwr\FilamentQt5Theme\Support;

use Filament\Support\Colors\Color;

class ThemeConfigResolver
{
    private const COMFORTABLE_DENSITY = [
        'text-xs' => '0.75rem',
        'text-sm' => '0.875rem',
        'text-base' => '0.9375rem',
        'text-md' => '1rem',
        'text-lg' => '1.125rem',
        'text-xl' => '1.25rem',
        'text-2xl' => '1.5rem',
        'text-3xl' => '1.875rem',
        'text-4xl' => '2.25rem',
        'khwr-qt-sp1' => '3px',
        'khwr-qt-sp2' => '6px',
        'khwr-qt-sp3' => '8px',
        'khwr-qt-sp4' => '10px',
        'khwr-qt-sp5' => '12px',
        'khwr-qt-sp6' => '14px',
        'khwr-qt-sp8' => '20px',
        'khwr-qt-sp10' => '24px',
        'khwr-qt-sp12' => '28px',
        'khwr-qt-sp16' => '40px',
    ];

    /**
     * @var array<string, string>
     */
    private const SURFACE_KEY_MAP = [
        'window' => 'khwr-qt-window-bg',
        'widget' => 'khwr-qt-widget-bg',
        'toolbarBackground' => 'khwr-qt-toolbar-bg',
        'toolbarBorder' => 'khwr-qt-toolbar-border',
        'menubar' => 'khwr-qt-menubar-bg',
        'tableHeader' => 'khwr-qt-header-bg',
        'tab' => 'khwr-qt-tab-bg',
        'tabActive' => 'khwr-qt-tab-bg-active',
        'tabBorder' => 'khwr-qt-tab-border',
        'rowAlt' => 'khwr-qt-row-alt',
        'rowSelected' => 'khwr-qt-row-selected',
        'rowSelectedText' => 'khwr-qt-row-selected-text',
        'gridLine' => 'khwr-qt-grid-line',
        'inputBackground' => 'khwr-qt-input-bg',
        'inputBorder' => 'khwr-qt-input-border',
        'buttonBackground' => 'khwr-qt-btn-bg',
        'buttonBackgroundHover' => 'khwr-qt-btn-bg-hover',
        'buttonBackgroundPressed' => 'khwr-qt-btn-bg-pressed',
        'buttonBorder' => 'khwr-qt-btn-border',
    ];

    /**
     * @param  array{
     *     accentColor?: ?string,
     *     typography?: array{sans?: ?string, mono?: ?string},
     *     density?: ?string,
     *     radius?: ?string,
     *     semanticColors?: array{success?: ?string, error?: ?string, warning?: ?string},
     *     neutralColor?: ?string,
     *     surface?: array<string, string>,
     * }  $config
     * @return array<string, string>
     */
    public static function resolve(array $config): array
    {
        $variables = [];

        if (filled($config['accentColor'] ?? null)) {
            $variables = [...$variables, ...self::resolveAccentColor($config['accentColor'])];
        }

        $sans = $config['typography']['sans'] ?? null;
        $mono = $config['typography']['mono'] ?? null;

        if (filled($sans)) {
            $variables['font-sans'] = $sans;
        }

        if (filled($mono)) {
            $variables['font-mono'] = $mono;
        }

        if (($config['density'] ?? null) === 'comfortable') {
            $variables = [...$variables, ...self::COMFORTABLE_DENSITY];
        }

        if (($config['radius'] ?? null) === 'rounded') {
            $variables['khwr-qt-radius'] = '8px';
        }

        $variables = [...$variables, ...self::resolveSemanticColors($config['semanticColors'] ?? [])];

        if (filled($config['neutralColor'] ?? null)) {
            $variables = [...$variables, ...self::paletteShades('slate', $config['neutralColor'])];
        }

        $variables = [...$variables, ...self::resolveSurface($config['surface'] ?? [])];

        return $variables;
    }

    /**
     * @return array<string, string>
     */
    private static function resolveAccentColor(string $color): array
    {
        $palette = self::paletteShades('steel', $color);

        return [
            ...$palette,
            'khwr-qt-btn-bg-primary' => "linear-gradient(to bottom, {$palette['steel-400']} 0%, {$palette['steel-500']} 100%)",
            'khwr-qt-btn-bg-primary-hover' => "linear-gradient(to bottom, {$palette['steel-300']} 0%, {$palette['steel-400']} 100%)",
            'khwr-qt-btn-border-primary' => $palette['steel-600'],
            'khwr-qt-input-border-focus' => $palette['steel-500'],
            'khwr-qt-titlebar-active' => $palette['steel-500'],
            'color-primary' => $palette['steel-500'],
            'color-primary-hover' => $palette['steel-600'],
            'color-primary-active' => $palette['steel-700'],
        ];
    }

    /**
     * @param  array{success?: ?string, error?: ?string, warning?: ?string}  $colors
     * @return array<string, string>
     */
    private static function resolveSemanticColors(array $colors): array
    {
        $map = [
            'success' => 'color-success',
            'error' => 'color-error',
            'warning' => 'color-warning',
        ];

        $variables = [];

        foreach ($map as $key => $variable) {
            if (filled($colors[$key] ?? null)) {
                $variables[$variable] = $colors[$key];
            }
        }

        return $variables;
    }

    /**
     * @param  array<string, string>  $surface
     * @return array<string, string>
     */
    private static function resolveSurface(array $surface): array
    {
        $variables = [];

        foreach (self::SURFACE_KEY_MAP as $key => $variable) {
            if (filled($surface[$key] ?? null)) {
                $variables[$variable] = $surface[$key];
            }
        }

        return $variables;
    }

    /**
     * Generates the 50..900 shades of Filament's own palette algorithm
     * (the same one behind ->colors()) and keys them as "{prefix}-{step}".
     *
     * @return array<string, string>
     */
    private static function paletteShades(string $prefix, string $color): array
    {
        $palette = Color::generatePalette($color);
        $shades = [];

        foreach ([50, 100, 200, 300, 400, 500, 600, 700, 800, 900] as $step) {
            $shades["{$prefix}-{$step}"] = $palette[$step];
        }

        return $shades;
    }

    /**
     * @param  array{accentColor?: ?string, neutralColor?: ?string}  $config
     * @return array<string, string>
     */
    public static function resolveDark(array $config): array
    {
        $variables = [];

        if (filled($config['accentColor'] ?? null)) {
            $variables = [...$variables, ...self::resolveAccentColorDark($config['accentColor'])];
        }

        if (filled($config['neutralColor'] ?? null)) {
            $palette = Color::generatePalette($config['neutralColor']);
            $variables['slate-300'] = $palette[200];
        }

        return $variables;
    }

    /**
     * @return array<string, string>
     */
    private static function resolveAccentColorDark(string $color): array
    {
        $palette = Color::generatePalette($color);

        return [
            'steel-500' => $palette[400],
            'steel-600' => $palette[500],
            'khwr-qt-btn-bg-primary' => "linear-gradient(to bottom, {$palette[300]} 0%, {$palette[400]} 100%)",
            'khwr-qt-btn-bg-primary-hover' => "linear-gradient(to bottom, {$palette[200]} 0%, {$palette[300]} 100%)",
            'khwr-qt-btn-border-primary' => $palette[500],
            'khwr-qt-input-border-focus' => $palette[400],
            'khwr-qt-titlebar-active' => $palette[400],
            'color-primary' => $palette[400],
            'color-primary-hover' => $palette[500],
            'color-primary-active' => $palette[600],
        ];
    }

    /**
     * @param  array<string, string>  $variables
     */
    public static function toStyleBlock(string $selector, array $variables): string
    {
        $declarations = '';

        foreach ($variables as $name => $value) {
            $declarations .= "--{$name}:{$value};";
        }

        return "<style>{$selector}{{$declarations}}</style>";
    }
}
