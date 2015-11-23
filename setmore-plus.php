<?php
/**
 * Plugin Name: SetMore Plus
 * Plugin URI: https://www.wpmission.com/plugin/setmore-plus
 * Description: Easy online appointments with a widget, shortcode, or menu link.
 * Version: 3.0
 * Author: Chris Dillon
 * Author URI: https://www.wpmission.com
 * Text Domain: setmore-plus
 * Requires: 3.5 or higher
 * License: GPLv3 or later
 *
 * Copyright 2014-2015  Chris Dillon  chris@wpmission.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


class SetmorePlus {

	function __construct() {

		if ( ! defined( 'SETMOREPLUS_URL' ) )
			define( 'SETMOREPLUS_URL', plugin_dir_url( __FILE__ ) );

		if ( ! defined( 'SETMOREPLUS_DIR' ) )
			define( 'SETMOREPLUS_DIR', plugin_dir_path( __FILE__ ) );

		require_once SETMOREPLUS_DIR . 'inc/class-widget.php';

		load_plugin_textdomain( 'setmore-plus', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		add_action( 'admin_init', array( $this, 'settings_init' ) );
		add_action( 'admin_init', array( $this, 'default_settings' ) );

		add_action( 'init', array( $this, 'register_shortcodes' ) );

		add_action( 'widgets_init', array( $this, 'register_widget' ) );

		add_action( 'load-widgets.php', array( $this, 'load_widget_scripts' ) );

		add_action( 'load-settings_page_setmoreplus', array( $this, 'load_settings_scripts' ) );

		add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );

		add_filter( 'no_texturize_shortcodes', array( $this, 'shortcodes_to_exempt_from_wptexturize' ) );

		/**
		 * Show the popup from a menu link.
		 *
		 * @since 3.0
		 */
		add_action( 'wp', array( $this, 'popup_script' ) );

	}

	public function load_widget_scripts() {
		wp_enqueue_style( 'setmoreplus-widget-style', plugins_url( 'css/widget.css', __FILE__ ) );
		wp_enqueue_style( 'setmoreplus-widget-admin', plugins_url( 'css/widget-admin.css', __FILE__ ) );
	}

	public function load_settings_scripts() {
		wp_enqueue_style( 'setmoreplus-admin', plugins_url( 'css/admin.css', __FILE__ ) );
		wp_enqueue_style( 'colorbox-style', SETMOREPLUS_URL . 'inc/colorbox/colorbox.css' );
		wp_enqueue_script( 'colorbox-script', SETMOREPLUS_URL . 'inc/colorbox/jquery.colorbox-min.js', array( 'jquery' ) );
	}

	public function default_settings() {

		$plugin_data = get_plugin_data( __FILE__, false );
		$plugin_version = $plugin_data['Version'];
		if ( isset( $options['plugin_version'] ) && $options['plugin_version'] == $plugin_version )
			return;

		$default_options = array(
			'url'    => '',
			'width'  => 540,
			'height' => 680,
			'lnt'    => 1,
		);

		// Updating from 2.1
		$previous_setting = get_option( 'setmoreplus_url' );
		if ( $previous_setting ) {
			$default_options['url'] = $previous_setting;
			delete_option( 'setmoreplus_url' );
		}

		$options = get_option( 'setmoreplus' );
		if ( ! $options ) {
			// New activation
			update_option( 'setmoreplus', $default_options );
		} else {
			// New options
			$options = array_merge( $default_options, $options );
			$options['plugin_version'] = $plugin_version;
			update_option( 'setmoreplus', $options );
		}

	}

	public function plugin_action_links( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ){
			$settings_link = '<a href="options-general.php?page=setmoreplus">' . __( 'Settings', 'setmore-plus' ) . '</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;
	}

	public function add_admin_menu() {
		add_options_page( 'SetMore Plus Options', 'SetMore Plus', 'manage_options', 'setmoreplus', array( $this, 'options_page' ) );
}

	public function settings_init() {

		register_setting( 'setmoreplus_group', 'setmoreplus', array( $this, 'sanitize_options' ) );

		add_settings_section(
			'setmoreplus_section',
			'',
			'',
			'setmoreplus_group'
		);

		add_settings_field(
			'setmoreplus-url',
			'Your SetMore URL',
			array( $this, 'render_setting_url' ),
			'setmoreplus_group',
			'setmoreplus_section'
		);

		add_settings_field(
			'setmoreplus-width',
			'Popup width',
			array( $this, 'render_setting_width' ),
			'setmoreplus_group',
			'setmoreplus_section'
		);

		add_settings_field(
			'setmoreplus-height',
			'Popup height',
			array( $this, 'render_setting_height' ),
			'setmoreplus_group',
			'setmoreplus_section'
		);

		add_settings_field(
			'setmoreplus-lnt',
			'Leave No Trace',
			array( $this, 'render_setting_lnt' ),
			'setmoreplus_group',
			'setmoreplus_section'
		);

	}

	public function sanitize_options( $input ) {
		$input['url']    = sanitize_text_field( $input['url'] );
		$input['width']  = sanitize_text_field( $input['width'] );
		$input['height'] = sanitize_text_field( $input['height'] );
		$input['lnt']    = isset( $input['lnt'] ) ? $input['lnt'] : 0;
		return $input;
	}

	public function render_setting_url() {
		$options = get_option( 'setmoreplus' );
		?>
		<input type="text" id="setmoreplus_url" name="setmoreplus[url]" style="width: 310px;" value="<?php echo $options['url']; ?>" placeholder="SetMore Booking Page URL" />

		<p class="help">To find your unique URL, <a href="http://my.setmore.com" target="_blank">sign in to SetMore</a> and click on the Profile tab. Or get started with <a href="http://www.setmore.com" target="_blank">a completely free account</a>.</p>
		<?php
	}

	public function render_setting_width() {
		$options = get_option( 'setmoreplus' );
		?>
		<div>
			<input type="text" id="setmoreplus_width" class="four-digits" name="setmoreplus[width]" value="<? echo $options['width']; ?>" />
		</div>
		<?php
	}

	public function render_setting_height() {
		$options = get_option( 'setmoreplus' );
		?>
		<div>
			<input type="text" id="setmoreplus_height" class="four-digits" name="setmoreplus[height]" value="<? echo $options['height']; ?>" />
		</div>
		<?php
	}

	public function render_setting_lnt() {
		$options = get_option( 'setmoreplus' );
		?>
		<div id="leave-no-trace">
			<label>
				<select name="setmoreplus[lnt]" autocomplete="off">
					<option value="1" <?php selected( $options['lnt'], 1 ); ?>>
						<?php _e( 'YES - Deleting this plugin will also delete these settings.', 'setmore-plus' ); ?>
					</option>
					<option value="0" <?php selected( $options['lnt'], 0 ); ?>>
						<?php _e( 'NO - These settings will remain after deleting this plugin.', 'setmore-plus' ); ?>
					</option>
				</select>
			</label>

			<p>&nbsp;<em><?php _e( 'Deactivating this plugin will not delete anything.', 'setmore-plus' ); ?></em>
			</p>
		</div>
		<?php
	}

	public function options_page() {
		if ( ! current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
		<div class="wrap setmore">
			<h2><?php _e( 'SetMore Plus', 'setmore-plus' ); ?></h2>
			<?php
			$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : '';
			$page = '?page=setmoreplus';
			?>
			<h2 class="nav-tab-wrapper">
				<a href="<?php echo $page; ?>" class="nav-tab <?php echo $tab == '' ? 'nav-tab-active' : ''; ?>">
					<?php _e( 'Settings', 'strong-testimonials' ); ?>
				</a>
				<a href="<?php echo $page; ?>&tab=instructions" class="nav-tab <?php echo $tab == 'instructions' ? 'nav-tab-active' : ''; ?>">
					<?php _e( 'Instructions', 'strong-testimonials' ); ?>
				</a>
			</h2>
			<?php
			switch ( $tab ) {
				case 'instructions':
					include SETMOREPLUS_DIR . 'inc/instructions.php';
					break;
				default:
					include SETMOREPLUS_DIR . 'inc/settings.php';
			}
			?>
		</div>
		<?php
	}

	public function register_widget() {
		register_widget( 'SetmorePlus_Widget' );
	}

	public function register_shortcodes() {
		add_shortcode( 'setmoreplus', array( $this, 'render_popup' ) );
	}

	public function render_popup( $atts, $content = '' ) {
		$options = get_option( 'setmoreplus' );
		$this->popup_script();

		extract( shortcode_atts(
			array(
				'button' => '',
				'link'   => '',
				'class'  => '',
			),
			$this->normalize_empty_atts( $atts ), 'setmoreplus'
		) );

		$content = $content ? $content : 'Book Appointment';

		/**
		 * CSS classes
		 *
		 * .setmore : style only
		 * .setmore-iframe : for Colorbox
		 */
		$classes = join( ' ', array_merge( array( 'setmore', 'setmore-iframe' ), explode( ' ', $class ) ) );

		if ( $link ) {

			$html = sprintf( '<a class="%s" href="%s">%s</a>', $classes, $options['url'], $content );

		} elseif ( $button ) {

			// href is not a valid attribute for <input> but Colorbox needs it to load the target page
			$html = sprintf( '<input type="button" class="%s" href="%s" value="%s" />', $classes, $options['url'], $content );

		} else {

			// load an iframe in the page
			$html = sprintf( '<iframe src="%s" width="%s" height="%s" frameborder="0"></iframe>', $options['url'], $width, $height );

		}
		return $html;
	}

	/**
	 * Display lightbox
	 *
	 * @since 2.3.0
	 */
	public function popup_script() {
		$options = get_option( 'setmoreplus' );
		wp_enqueue_style( 'colorbox-style', SETMOREPLUS_URL . 'inc/colorbox/colorbox.css' );
		wp_enqueue_script( 'colorbox-script', SETMOREPLUS_URL . 'inc/colorbox/jquery.colorbox-min.js', array( 'jquery' ) );
		$var = array(
			'iframe'      => true,
			'transition'  => 'elastic',
			'speed'       => 200,
			'height'      => $options['height'],
			'width'       => $options['width'],
			'opacity'     => 0.8,
			'returnFocus' => false,
			'rel'         => false,
		);
		wp_localize_script( 'colorbox-script', 'setmoreplus', $var );
		add_action( 'wp_footer', array( $this, 'call_colorbox' ), 100 );
	}

	public function call_colorbox() {
		?>
		<!-- SetMore Plus plugin -->
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$("a.setmore-iframe").add("input.setmore-iframe").add("li.setmore-iframe > a").colorbox(setmoreplus);
			});
		</script>
		<?php
	}

	/**
	 * Do not texturize shortcode.
	 *
	 * For WordPress 4.0.1+
	 * @since 2.2.2
	 * @param $shortcodes
	 * @return array
	 */
	public function shortcodes_to_exempt_from_wptexturize( $shortcodes ) {
		$shortcodes[] = 'setmoreplus';
		return $shortcodes;
	}

	/**
	 * Normalize empty shortcode attributes.
	 *
	 * Turns atts into tags - brilliant!
	 * Thanks http://wordpress.stackexchange.com/a/123073/32076
	 *
	 * @since 2.3.0
	 * @param $atts
	 * @return array
	 */
	public function normalize_empty_atts( $atts ) {
		if ( ! empty( $atts ) ) {
			foreach ( $atts as $attribute => $value ) {
				if ( is_int( $attribute ) ) {
					$atts[ strtolower( $value ) ] = true;
					unset( $atts[ $attribute ] );
				}
			}
			return $atts;
		}
	}

}

new SetmorePlus();
