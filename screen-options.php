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

		add_action( "load-$admin_page", [ $this, 'get_screen_options' ] );
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
	 * Array of screen options to display.
	 *
	 * @return array The screen option function names.
	 */
	private function screen_options() {
		$screen_options = [];

		foreach ( $this->options() as $option_name ) {
			$screen_options[] = [
				'option' => $option_name,
				'title'  => ucwords( $option_name ),
			];
		}

		return $screen_options;
	}

	/**
	 * Register the screen options.
	 */
	public function get_screen_options() {
		$screen = get_current_screen();

		if ( ! is_object( $screen ) || $screen->id !== self::$admin_page ) {
			return;
		}

		// Loop through all the options and add a screen option for each.
		foreach ( $this->options() as $option_name ) {
			add_screen_option( "wordpress_screen_options_demo_$option_name", [
				'option'  => $option_name,
				'default' => true,
				'value'   => true,
			] );
		}
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

	/**
	 * The HTML markup to wrap around each option.
	 */
	public function before() {
		?>
		<fieldset>
			<input type="hidden" name="wp_screen_options_nonce" value="<?php echo esc_textarea( wp_create_nonce( 'wp_screen_options_nonce' ) ); ?>">
			<legend><?php esc_html_e( 'WordPress Screen Options Demo', 'wp-screen-options-framework' ); ?></legend>
			<div class="metabox-prefs">
				<div><input type="hidden" name="wp_screen_options[option]" value="wordpress_screen_options_demo_options" /></div>
				<div><input type="hidden" name="wp_screen_options[value]" value="yes" /></div>
				<div class="wordpress_screen_options_demo_custom_fields">
		<?php
	}

	/**
	 * The HTML markup to close the options.
	 */
	public function after() {
		$button = get_submit_button( __( 'Apply', 'wp-screen-options-framework' ), 'button', 'screen-options-apply', false );
		?>
				</div><!-- wordpress_screen_options_demo_custom_fields -->
			</div><!-- metabox-prefs -->
		</fieldset>
		<br class="clear">
		<?php
		echo $button; // WPCS: XSS ok.
	}

	/**
	 * Display a screen option.
	 *
	 * @param  string $title  The title to display.
	 * @param  string $option The name of the option we're displaying.
	 */
	public function show_option( $title, $option ) {
		$screen    = get_current_screen();
		$id        = "wordpress_screen_options_demo_$option";
		$default   = $screen->get_option( $id, 'value' );
		$user_meta = get_usermeta( get_current_user_id(), 'wordpress_screen_options_demo_options' );

		// Check if the screen options have been saved. If so, use the saved value. Otherwise, use the default values.
		if ( $user_meta ) {
			$checked = array_key_exists( $option, $user_meta ) ? 'checked="checked"' : '';
		} else {
			$checked = $screen->get_option( $id, 'value' ) ? 'checked="checked"' : '';
		}

		?>

		<label for="<?php echo esc_textarea( $id ); ?>"><input type="checkbox" name="wordpress_screen_options_demo[<?php echo esc_textarea( $option ); ?>]" class="wordpress-screen-options-demo" id="<?php echo esc_textarea( $id ); ?>" <?php echo esc_textarea( $checked ); ?> /> <?php echo esc_html( $title ); ?></label>

		<?php
	}
}

add_action( 'plugins_loaded', array( 'WordPressScreenOptionsFramework', 'get_instance' ) );
