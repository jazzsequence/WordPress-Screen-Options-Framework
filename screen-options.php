<?php
/**
 * WordPress Screen Options Framework
 *
 * Boilerplate framework for building and extending Screen Options in the WordPress admin.
 *
 * @author  Chris Reynolds <chris@hmn.md>
 * @package WordPressScreenOptionsFramework
 * @version 1.0.0
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/*
	Copyright (C) 2018  Chris Reynolds  <chris@hmn.md>

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Screen Options Framework class.
 */
class WordPressScreenOptionsFramework {

	/**
	 * Singleton class instance.
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  1.0.0
	 * @return WordPressScreenOptionsFramework A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	private function __construct() {}

}

add_action( 'plugins_loaded', array( 'WordPressScreenOptionsFramework', 'get_instance' ) );
