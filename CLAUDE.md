# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Build & Development Commands

```bash
npm run build          # Production build (JS + SCSS via @wordpress/scripts)
npm run watch          # Dev mode with file watching (alias: npm start)
composer phpcs         # Run PHPCS against all PHP files
composer phpcbf        # Auto-fix PHPCS violations
wp i18n make-pot . ./languages/wpbe.pot --domain=wpbe --exclude=vendor,node_modules,build
wp i18n make-mo ./languages   # Compile all .po files to .mo
```

## Architecture

WordPress plugin using PSR-4 autoloading under the `WPBE\` namespace. The autoloader maps `WPBE\` to `classes/`.

### Initialization Chain

`wp-basic-elements.php` → `WPBE\WPBE::instance()` (loads Settings + Optimization on `plugins_loaded`)

### Section Pattern

Each feature lives in `classes/Optimization/Section/`. All 8 sections follow this pattern:

1. Constructor reads `get_option('wpbe_{name}')` and conditionally hooks into WordPress
2. A static method returns the available options (used by both the section itself and Settings for rendering)
3. Instance methods execute the actual WordPress modifications

Settings.php calls the static methods to build the UI, and the section classes read the saved options at runtime. This keeps the source of truth in one place per section.

### Settings System

`classes/Settings/Settings.php` registers all 8 sections via the WordPress Settings API under option group `wpbe_settings_group`, page slug `wpbe-settings`. Each section has its own `register_*`, `sanitize_*`, `render_*_section`, and `render_*_field` methods. The render method outputs a sidebar-nav layout with tab panels (JS handles switching, persisted via localStorage).

### Option Names

All stored in `wp_options`: `wpbe_admin_bar`, `wpbe_admin_dashboard`, `wpbe_admin_footer`, `wpbe_admin_user_profile`, `wpbe_comments`, `wpbe_gutenberg`, `wpbe_meta_tags`, `wpbe_rest_api_whitelist`. These are all cleaned up by `uninstall.php`.

## Conventions

- Text domain: `wpbe`
- Constants: `WPBE_VERSION`, `WPBE_URL`, `WPBE_PATH`, `WPBE_BUILD_URL`, `WPBE_BUILD_PATH`, `WPBE_BASENAME`
- All translation strings use `esc_html__()` or `esc_attr__()` — never bare `__()`
- All output escaped at point of render
- PHPCS standard: WordPress-Core with short arrays, short ternary, and non-Yoda conditions allowed (see `phpcs.xml`)
- Assets in `assets/scss/` and `assets/js/` compile to `build/css/` and `build/js/` — files prefixed with `_` are treated as partials
- `build/` is gitignored; admin CSS/JS enqueued only on the WPBE settings page

## Adding a New Section

1. Create `classes/Optimization/Section/NewSection.php` with a static method returning options and a constructor that reads `get_option('wpbe_new_section')`
2. Instantiate it in `classes/Optimization/Optimization.php`
3. Add `register_new_section_settings()`, `sanitize_new_section()`, `render_new_section_section()`, and `render_new_section_field()` in `classes/Settings/Settings.php`
4. Add the option name to `uninstall.php`
5. Regenerate the POT file
