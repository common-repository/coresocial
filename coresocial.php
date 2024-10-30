<?php
/**
 * Plugin Name:       coreSocial: Social Networks Sharing
 * Plugin URI:        https://www.dev4press.com/plugins/coresocial/
 * Description:       Add popular social networks share buttons to posts and pages, lists social network profiles with customizable styling and full block editor support.
 * Author:            Milan Petrovic
 * Author URI:        https://www.dev4press.com/
 * Text Domain:       coresocial
 * Version:           1.1
 * Requires at least: 6.1
 * Tested up to:      6.6
 * Requires PHP:      7.4
 * License:           GPLv3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package coreSocial
 *
 * == Copyright ==
 * Copyright 2008 - 2024 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

use Dev4Press\v50\WordPress;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const CORESOCIAL_FILE = __FILE__;
const CORESOCIAL_PATH = __DIR__ . '/';
const CORESOCIAL_D4PLIB = __DIR__ . '/library/';
const CORESOCIAL_BLOCKS_PATH = CORESOCIAL_PATH . 'build/blocks/';

define( 'CORESOCIAL_URL', plugins_url( '/', CORESOCIAL_FILE ) );

require_once CORESOCIAL_D4PLIB . 'core.php';

require_once CORESOCIAL_PATH . 'core/autoload.php';
require_once CORESOCIAL_PATH . 'core/bridge.php';
require_once CORESOCIAL_PATH . 'core/functions.php';

coresocial();
coresocial_settings();

if ( WordPress::instance()->is_admin() ) {
	coresocial_admin();
}

if ( WordPress::instance()->is_ajax() ) {
	coresocial_ajax();
}
