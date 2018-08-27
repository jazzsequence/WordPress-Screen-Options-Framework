<?php
/**
 * Plugin Name: WordPress Screen Options Framework
 * Description: A plugin to demo a framework for building and extending Screen Options in the WordPress admin.
 * Plugin URI: https://github.com/jazzsequence/WordPress-Screen-Options-Framework
 * Author: Chris Reynolds
 * Author URI: https://chrisreynolds.io
 * Version: 1.0.0
 * License: GPL3
 * Text Domain: wordpress-screen-options-demo
 *
 * @author  Chris Reynolds <chris@hmn.md>
 * @package WordPressScreenOptionsFramework
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
	 * The name of your admin page.
	 *
	 * @var string
	 */
	public static $admin_page = 'toplevel_page_screen_options_demo_page';

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
	private function __construct() {
		$admin_page = self::$admin_page;

		add_action( 'admin_menu', [ $this, 'add_admin_page' ] );

	/**
	 * Return an array of options. Replace this with your own options, structured however you like.
	 *
	 * @return array An array of fake options.
	 */
	private function options() {
		return [
			'foo',
			'bar',
			'baz',
		];
	}

	/**
	 * Adds an admin page.
	 */
	public function add_admin_page() {
		add_menu_page(
			esc_html__( 'WordPress Screen Options Demo Page', 'wordpress-screen-options-demo' ), // Admin page title.
			esc_html__( 'Screen Options Demo', 'wordpress-screen-options-demo' ), // Menu title.
			'manage_options', // Capability.
			'screen_options_demo_page', // Page slug.
			[ $this, 'admin_page' ], // Callback function.
			'dashicons-lightbulb', // Icon.
			15 // Position.
		);
	}

	/**
	 * Renders the admin page.
	 */
	public function admin_page() {
		$screen    = get_current_screen();
		$parent    = get_admin_page_parent();
		$user_meta = get_usermeta( get_current_user_id(), 'wordpress_screen_options_demo_options' );
		?>
		<div class="wrap <?php echo esc_attr( $parent ); ?>">
			<h1><?php echo esc_attr( get_admin_page_title() ); ?></h1>

			<div class="notice notice-info is-dismissable">
				<p>
					<?php
					// Translators: %s is the URL to the GitHub repository.
					echo wp_kses_post( sprintf( __( 'This is a demonstration of the Screen Options framework. More information can be found at <a href="%s">the GitHub repo</a>. Click on the Screen Options tab in the upper right hand corner of the page to test the screen options.', 'wordpress-screen-options-demo' ), 'https://github.com/jazzsequence/WordPress-Screen-Options-Framework' ) );
					?>
				</p>
			</div> <!-- .notice -->
		</div> <!-- .<?php echo esc_attr( $parent ); ?> -->
		<div class="<?php echo esc_attr( $parent ); ?>-body">
			<h2><?php esc_html_e( 'Screen option values', 'wordpress-screen-options-demo' ); ?></h2>
			<div class="description">
				<?php if ( $user_meta ) : ?>
					<p>
						<?php esc_html_e( 'Screen Options have been saved to user meta. Displaying the user settings below.', 'wordpress-screen-options-demo' ); ?>
					</p>
				<?php else : ?>
					<p>
						<?php esc_html_e( 'Screen Options have not yet been saved for this user. Displaying the default settings below.', 'wordpress-screen-options-demo' ); ?>
					</p>
				<?php endif; ?>
			</div>
			<ul class="screen-options-list">
				<?php
				foreach ( $this->options() as $option_name ) {
					$option     = "wordpress_screen_options_demo_$option_name";
					if ( $user_meta ) {
						$user_value = isset( $user_meta[ $option_name ] ) ? 'true' : 'false';
					} else {
						$user_value = var_export( $screen->get_option( $option, 'value' ), true );
					}
					?>
					<li class="<?php echo esc_attr( $option_name ); ?>-option">
						<strong><?php echo esc_attr( ucwords( $option_name ) ); ?>:</strong> <code><?php echo esc_html( $user_value ); ?></code>
					</li>
				<?php } ?>
			</ul>
		</div>
		<?php

	}
}

add_action( 'plugins_loaded', array( 'WordPressScreenOptionsFramework', 'get_instance' ) );
