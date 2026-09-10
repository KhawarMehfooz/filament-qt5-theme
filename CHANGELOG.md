# Changelog

All notable changes to this package will be documented in this file.

## [1.0.2]

### Fixed
- Searchable select dropdowns not showing any options on click. `.fi-input-wrp`
  had `overflow: hidden` (added to clip the prefix/suffix icon boxes to the
  wrapper's rounded corners), which also clipped the select's
  absolutely-positioned options panel to zero visible height. Replaced it with
  `border-radius` on the wrapper's first/last children so the icon boxes still
  get rounded corners without clipping the dropdown.

## [1.0.1]

### Changed
- Prefix/suffix icon sections on inputs and selects now get a differentiated
  background color instead of blending into the input.

## [1.0.0]

Initial release.

A Qt5-inspired desktop theme for Filament panels (v4 and v5), with full
light and dark mode support.

### Themed components
Buttons, text inputs, selects (native + searchable), badges, tabs, tables,
pagination, icon-buttons, checkboxes/radios, toggles, sections, dropdowns
(context menus), file-upload (drop zone), modals, notifications (toasts),
tooltips, sidebar, topbar, and auth screens (login, registration, password
reset, email verification, profile, 2FA).

### Requirements
- PHP ^8.2
- Filament ^4.0 | ^5.0

[1.0.2]: https://github.com/KhawarMehfooz/filament-qt5-theme/releases/tag/v1.0.2
[1.0.1]: https://github.com/KhawarMehfooz/filament-qt5-theme/releases/tag/v1.0.1
[1.0.0]: https://github.com/KhawarMehfooz/filament-qt5-theme/releases/tag/v1.0.0
