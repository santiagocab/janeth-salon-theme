=== Janeth Salon Theme ===
Contributors: santiagocab
Tags: blog, wide-blocks, block-patterns, block-styles, custom-colors, custom-logo, editor-style, featured-images, full-site-editing, style-variations, translation-ready
Requires at least: 6.1
Tested up to: 6.4
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Custom Block Ready Theme for a hair salon

== Copyright ==

Janeth Salon, Copyright 2025 - present Santiago Cabrera
Janeth Salon is distributed under the terms of the GNU GPLv2 or later

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

== Changelog ==

= 1.0.1 - October 2025 =
* Add translation

= 1.0.0 - September 2025 =
* Initial release

== Credits ==
* Based on Create Block Theme https://wordpress.org/plugins/create-block-theme/, (C) 2022 - present WordPress.org, License [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)

== Resources ==
All Illustrations by chatGPT

### PHPCS Setup
1. Install composer if you don't have it
2. Run composer install from root folder

### PHPCS Usage
* Scan everything (except for files excluded in phpcs.xml.dist) : `vendor/bin/phpcs . -v --colors -s`
* Scan one directory : `vendor/bin/phpcs template-parts/ -v --colors -s`
* Scan one file : `vendor/bin/phpcs includes/optimizations/featured-image.php -v --colors -s`
* Run fixes for everything (except for files excluded in phpcs.xml.dist) : `vendor/bin/phpcbf . -v --colors -s`
* Run fixes for one directory : `vendor/bin/phpcbf template-parts/ -v --colors -s`
* Run fixes for one file : `vendor/bin/phpcbf single.php -v --colors -s`
