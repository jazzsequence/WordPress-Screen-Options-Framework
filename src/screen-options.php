<?php
/**
 * WordPress Screen Options Framework
 *
 * Boilerplate include for extending and creating Screen Options in the WordPress admin.
 *
 * @version 1.0.0
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
	 * The class instance.
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
	 * The class constructor.
	 */
	private function __construct() {
		$admin_page = WordPressScreenOptionsDemo::$admin_page;

		add_action( "load-$admin_page", [ $this, 'get_screen_options' ] );
		add_filter( 'screen_settings', [ $this, 'show_screen_options' ], 10, 2 );
		add_filter( 'set-screen-option', [ $this, 'set_option' ], 11, 3 );
	}

	/**
	 * Array of screen options to display.
	 *
	 * @return array The screen option function names.
	 */
	private function screen_options() {
		$screen_options = [];

		foreach ( WordPressScreenOptionsDemo::get_instance()->options() as $option_name ) {
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

		if ( ! is_object( $screen ) || WordPressScreenOptionsDemo::$admin_page !== $screen->id ) {
			return;
		}

		// Loop through all the options and add a screen option for each.
		foreach ( WordPressScreenOptionsDemo::get_instance()->options() as $option_name ) {
			add_screen_option( "wordpress_screen_options_demo_$option_name", [
				'option'  => $option_name,
				'value'   => true,
			] );
		}
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
		$user_meta = get_usermeta( get_current_user_id(), 'wordpress_screen_options_demo_options' );

		// Check if the screen options have been saved. If so, use the saved value. Otherwise, use the default values.
		if ( $user_meta ) {
			$checked = array_key_exists( $option, $user_meta );
		} else {
			$checked = $screen->get_option( $id, 'value' ) ? true : false;
		}

		?>

		<label for="<?php echo esc_textarea( $id ); ?>"><input type="checkbox" name="wordpress_screen_options_demo[<?php echo esc_textarea( $option ); ?>]" class="wordpress-screen-options-demo" id="<?php echo esc_textarea( $id ); ?>" <?php checked( $checked ); ?>/> <?php echo esc_html( $title ); ?></label>

		<?php
	}

	/**
	 * Render the screen options block.
	 *
	 * @param  string $status The screen options markup.
	 * @param  object $args   An object of screen options data.
	 * @return string         The filtered screen options block.
	 */
	public function show_screen_options( $status, $args ) {
		if ( WordPressScreenOptionsDemo::$admin_page !== $args->base ) {
			return $status;
		}

		ob_start();

		$this->before();
		foreach ( $this->screen_options() as $screen_option ) {
			$this->show_option( $screen_option['title'], $screen_option['option'] );
		}
		$this->after();

		return ob_get_clean();
	}

	/**
	 * Save the screen option setting.
	 *
	 * @param string $status The default value for the filter. Using anything other than false assumes you are handling saving the option.
	 * @param string $option The option name.
	 * @param array  $value  Whatever option you're setting.
	 */
	public function set_option( $status, $option, $value ) {
		if ( isset( $_POST['wp_screen_options_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wp_screen_options_nonce'] ) ), 'wp_screen_options_nonce' ) ) {
			if ( 'wordpress_screen_options_demo_options' === $option ) {
				$value = isset( $_POST['wordpress_screen_options_demo'] ) && is_array( $_POST['wordpress_screen_options_demo'] ) ? $_POST['wordpress_screen_options_demo'] : []; // WPCS: Sanitization ok.
			}
		}

		return $value;
	}
}

// Fire it up!
WordPressScreenOptionsFramework::get_instance();
