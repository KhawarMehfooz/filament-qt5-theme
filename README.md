# Filament Qt5 Theme

A Qt5-inspired desktop theme for Filament panels. Supports Filament v4 and v5.

## Screenshots

![Dashboard (light)](screenshots/dashboard-light.jpg)
![Dashboard (dark)](screenshots/dashboard-dark.jpg)
![Orders table](screenshots/orders-table.jpg)
![Products table](screenshots/products-table.jpg)
![Table (dark)](screenshots/table-dark.jpg)
![Form](screenshots/form.png)

## Installation

```bash
composer require khwr/filament-qt5-theme
```

Register the plugin on your panel:

```php
use Khwr\FilamentQt5Theme\FilamentQt5ThemePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(FilamentQt5ThemePlugin::make());
}
```

Publish the compiled assets:

```bash
php artisan filament:assets
```

## Configuration

The theme ships with a default Qt5-inspired palette and density, but a
handful of tokens can be overridden from PHP without a CSS rebuild:

```php
FilamentQt5ThemePlugin::make()
    ->accentColor('#7A3D3D')          // any CSS color; drives buttons, focus rings, links
    ->typography(sans: 'Inter', mono: 'JetBrains Mono')
    ->density('comfortable')          // 'compact' (default) | 'comfortable'
    ->radius('rounded')               // 'sharp' (default) | 'rounded'
```

All of these are optional and additive — a panel that doesn't call any of
them renders exactly as before. `typography()`'s `googleFonts` parameter
(default `true`) controls whether the bundled IBM Plex Sans/Mono Google
Fonts stylesheet loads at all — set it to `false` if you're supplying your
own `sans`/`mono` fonts and don't want the extra external request, or if
you'd rather fall back to system fonts entirely.

`accentColor()` also applies to dark mode automatically: the panel's
light/dark theme switcher gets a lighter, more saturated shade of the same
seed color (not the same value duplicated onto a dark background). Nothing
else in this list has a dark-mode equivalent yet — `density()`/`radius()`
don't need one (dark mode doesn't change spacing/radius), and
`semanticColors()`/`surface()` are flat pass-through values with no seed
color to derive a dark-appropriate shade from, so dark mode keeps its own
built-in defaults for those until there's an explicit dark-override API.

Additional, more targeted overrides:

```php
FilamentQt5ThemePlugin::make()
    ->semanticColors(success: '#2E7D32', error: '#C62828', warning: '#B36A00')
    ->neutralColor('#6B7280') // regenerates the neutral gray scale (warm vs. cool gray)
    ->surface([
        'window' => '#E8EAEC',
        'widget' => '#F0F2F4',
        'toolbarBackground' => 'linear-gradient(to bottom, #EDEEF0 0%, #D8DADD 100%)',
        'toolbarBorder' => '#ABADB0',
        'menubar' => '#E4E6E9',
        'tableHeader' => 'linear-gradient(to bottom, #E8EAED 0%, #D4D6DA 100%)',
        'tab' => '#D4D7DB',
        'tabActive' => '#F0F2F4',
        'tabBorder' => '#ABADB0',
        'rowAlt' => '#EEF0F2',
        'rowSelected' => '#D6E4EF',
        'rowSelectedText' => '#1C1F23',
        'gridLine' => '#D4D7DB',
        'inputBackground' => '#FFFFFF',
        'inputBorder' => '#ABADB0',
        'buttonBackground' => 'linear-gradient(to bottom, #F4F5F7 0%, #DADCE0 100%)',
        'buttonBackgroundHover' => 'linear-gradient(to bottom, #F8F9FB 0%, #E2E4E8 100%)',
        'buttonBackgroundPressed' => 'linear-gradient(to bottom, #CBCDD2 0%, #D8DADD 100%)',
        'buttonBorder' => '#ABADB0',
    ]);
```

`semanticColors()` and `neutralColor()` accept any CSS color and are omitted
individually if not passed (e.g. `semanticColors(success: '...')` alone
leaves `error`/`warning` at their defaults). Like `accentColor()`,
`neutralColor()` also applies a lighter dark-mode variant automatically;
`semanticColors()` doesn't (see the dark-mode note above). `surface()` is a
flat pass-through — each key is optional, and any key you don't pass keeps its
current default (the values shown above *are* today's defaults). Unlike
`accentColor()`/`neutralColor()`, `surface()` values aren't derived from a
single seed color: these are hand-blended Qt5 gradients that don't sit on
one palette scale, so each slot is set directly.

None of these methods validate their input — an unrecognized `density()`/
`radius()` value or an unknown `surface()` key is silently ignored rather
than throwing, the same way Filament's own `->colors()` doesn't validate
color strings. Check a screenshot if an override doesn't seem to apply.

## Development

CSS source lives in `resources/css`. Build the published stylesheet with:

```bash
npm install
npm run build
```

## License

MIT. See [LICENSE.md](LICENSE.md).
