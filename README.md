# WP Basic Elements

Disable unnecessary WordPress features, clean up your markup, and simplify the admin. Everything is opt-in — nothing changes until you say so.

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/wp-basic-elements)](https://wordpress.org/plugins/wp-basic-elements/)
[![WordPress Plugin Rating](https://img.shields.io/wordpress/plugin/stars/wp-basic-elements)](https://wordpress.org/plugins/wp-basic-elements/)
[![License](https://img.shields.io/github/license/damircalusic/wp-basic-elements)](https://www.gnu.org/licenses/gpl-2.0.html)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.4-8892BF)](https://www.php.net/)
[![WordPress Version](https://img.shields.io/badge/wordpress-%3E%3D6.9-21759B)](https://wordpress.org/)

## What it does

WP Basic Elements gives you full control over the features WordPress loads by default. Disable what you don't need, clean up the front-end markup, and streamline the admin interface — all from a single, modern settings page.

Every option is **off by default**. You choose exactly what to disable, nothing more.

| Section | What you can control |
|---------|---------------------|
| **Admin Bar** | WP Logo, New Content, Customize, Updates, Comments, Edit, Site Editor, Search, Yoast SEO |
| **Dashboard Widgets** | Welcome Panel, At a Glance, Activity, Site Health, Quick Draft, WP Events & News, WooCommerce (5 widgets), Yoast SEO, Easy WP SMTP |
| **Admin Footer** | Left footer text, right footer text, hide version |
| **User Profile** | Profile fields (7 options) and contact methods (13 options) |
| **Comments** | Disable the entire comment system with a single toggle |
| **Gutenberg Editor** | Welcome Guide, Fullscreen Mode, Focus Mode, Distraction Free, Top Toolbar |
| **Meta Tags & Head** | Generator tag, oEmbed, shortlinks, WLW manifest, adjacent posts, REST API links, RSS feeds, emojis, pingbacks/trackbacks |
| **REST API** | Restrict access for non-logged-in users with a configurable whitelist |

## Installation

### From WordPress.org

1. Go to **Plugins > Add New** in your WordPress admin
2. Search for **WP Basic Elements**
3. Click **Install Now**, then **Activate**
4. Go to **Settings > WPBE Elements**

### Manual

1. Download the latest release
2. Upload the `wp-basic-elements` folder to `/wp-content/plugins/`
3. Activate through the **Plugins** menu
4. Go to **Settings > WPBE Elements**

## Development

### Requirements

- Node.js (for building assets)
- Composer (for PHPCS)
- PHP 8.4+

### Setup

```bash
npm install
composer install
```

### Commands

```bash
npm run build        # Production build
npm run watch        # Dev mode with file watching
composer phpcs       # Lint PHP files
composer phpcbf      # Auto-fix PHP lint issues
```

### Build Pipeline

Assets in `assets/js/` and `assets/scss/` are compiled via `@wordpress/scripts` to the `build/` directory. The webpack config auto-discovers entry files — files prefixed with `_` are treated as partials and excluded.

## Translations

Ships with 10 languages:

English, Swedish, Norwegian, Serbian, Danish, Croatian, German, Polish, Italian, Spanish, French

To generate translation files:

```bash
wp i18n make-pot . ./languages/wpbe.pot --domain=wpbe --exclude=vendor,node_modules,build
wp i18n make-mo ./languages
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make sure `composer phpcs` passes
4. Make sure `npm run build` succeeds
5. Submit a pull request

## License

GPLv2 or later. See [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html).

## Support

- [WordPress.org Support Forum](https://wordpress.org/support/plugin/wp-basic-elements/)
- [GitHub Issues](https://github.com/damircalusic/wp-basic-elements/issues)

## Donate

If this plugin saves you time, consider [buying me a coffee](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=AJABLMWDF4RR8&source=url).
