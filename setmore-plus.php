<?php
/**
 * Plugin Name: Setmore Plus
 * Plugin URI: https://www.wpmission.com/plugins/setmore-plus
 * Description: Easy online appointments with a widget, shortcode, or menu link.
 * Version: 3.6.1
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

		if ( ! defined( 'SETMOREPLUS_IMAGES' ) )
			define( 'SETMOREPLUS_IMAGES', plugin_dir_url( __FILE__ ) . '/images/' );

		require_once SETMOREPLUS_DIR . 'inc/class-widget.php';

		load_plugin_textdomain( 'setmore-plus', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'default_settings' ) );
			add_action( 'admin_init', array( $this, 'settings_init' ), 20 );
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

			add_action( 'load-settings_page_setmoreplus', array( $this, 'load_admin_scripts' ) );
			add_action( 'load-settings_page_setmoreplus', array( $this, 'load_lnt_style' ) );

			// Loading Colorbox to show a screenshot in a popup, not for the scheduler.
			add_action( 'load-settings_page_setmoreplus', array( $this, 'load_colorbox' ) );

			add_action( 'load-widgets.php', array( $this, 'load_widget_scripts' ) );

			add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );

			// LNT icon
			add_action( 'load-plugins.php', array( $this, 'load_lnt_style' ) );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );
		}

		add_action( 'init', array( $this, 'register_shortcodes' ) );
		add_action( 'wp_head', array( $this, 'show_version_info' ), 999 );
		add_filter( 'no_texturize_shortcodes', array( $this, 'shortcodes_to_exempt_from_wptexturize' ) );

		add_action( 'widgets_init', array( $this, 'register_widget' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'load_colorbox' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'setmoreplus_script' ) );

		add_action( 'wp_ajax_setmoreplus_add_url', array( $this, 'add_url_function' ) );
		add_action( 'wp_ajax_nopriv_setmoreplus_add_url', array( $this, 'add_url_function' ) );

		add_filter( 'setmoreplus_url', array( $this, 'url_filter' ), 10, 2 );

	}

	/**
	 * [Add New] Ajax receiver
	 */
	public function add_url_function() {
		$count = $_REQUEST['count'] + 1;
		$this->render_staff_url_row( $count );
		die();
	}

	public function load_lnt_style() {
		wp_enqueue_style( 'setmoreplus-lnt', plugins_url( '/css/lnt.css', __FILE__ ) );
	}

	public function load_colorbox() {
		wp_enqueue_style( 'colorbox-style', SETMOREPLUS_URL . 'inc/colorbox/colorbox.css' );
		$options = get_option( 'setmoreplus' );
		$defer   = is_admin() ? true : $options['defer'];
		wp_enqueue_script( 'colorbox-script', SETMOREPLUS_URL . 'inc/colorbox/jquery.colorbox-min.js', array( 'jquery' ), false, $defer );
	}

	public function load_scripts() {
		$options = get_option( 'setmoreplus' );
		wp_enqueue_script( 'setmoreplus-script', SETMOREPLUS_URL . 'js/setmoreplus.js', array( 'colorbox-script' ), $options['plugin_version'], $options['defer'] );
	}

	public function load_admin_scripts() {
		$options = get_option( 'setmoreplus' );
		wp_enqueue_style( 'setmoreplus-admin', SETMOREPLUS_URL . 'css/admin.css', array(), $options['plugin_version'] );
		wp_enqueue_script( 'setmoreplus-admin-script', SETMOREPLUS_URL . 'js/setmoreplus-admin.js', array( 'colorbox-script' ), $options['plugin_version'], $options['defer'] );
		wp_localize_script( 'setmoreplus-admin-script', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	public function load_widget_scripts() {
		wp_enqueue_style( 'setmoreplus-widget-admin', plugins_url( 'css/widget-admin.css', __FILE__ ) );
	}

	public function get_defaults() {
		return array(
			'url'                        => '',
			'staff_urls'                 => null,
			'width'                      => 585,
			'width_p'                    => 'px',
			'height'                     => 680,
			'height_p'                   => 'px',
			'mobile_breakpoint'          => 585,
			'embed_desktop_width'        => 585,
			'embed_desktop_width_p'      => 'px',
			'embed_desktop_height'       => 680,
			'embed_mobile_breakpoint'    => 585,
			'embed_mobile_height'        => 680,
			'defer'                      => 1,
			'lnt'                        => 1,
		);
	}

	public function default_settings() {
		$plugin_data    = get_plugin_data( __FILE__, false );
		$plugin_version = $plugin_data['Version'];
		if ( isset( $options['plugin_version'] ) && $options['plugin_version'] == $plugin_version )
			return;

		$default_options = $this->get_defaults();

		// Updating from 2.1
		$previous_setting = get_option( 'setmoreplus_url' );
		if ( $previous_setting ) {
			$default_options['url'] = $previous_setting;
			delete_option( 'setmoreplus_url' );
		}

		$options = get_option( 'setmoreplus' );
		if ( !$options ) {
			// New activation
			$options = $default_options;
		}
		else {
			// New options
			$options = array_merge( $default_options, $options );
		}
		$options['plugin_version'] = $plugin_version;
		update_option( 'setmoreplus', $options );
	}

	public function plugin_action_links( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ) {
			$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=setmoreplus' ), __( 'Settings', 'setmore-plus' ) );
			array_unshift( $links, $settings_link );
		}
		return $links;
	}

	/**
	 * Plugin meta row
	 *
	 * @param        $plugin_meta
	 * @param        $plugin_file
	 * @param array  $plugin_data
	 * @param string $status
	 *
	 * @since 3.5.0
	 *
	 * @return array
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data = array(), $status = '' ) {
		if ( $plugin_file == plugin_basename( __FILE__ ) ) {
			$plugin_meta[] = '<span class="lnt">Leave No Trace</span>';
		}
		return $plugin_meta;
	}

	public function add_admin_menu() {
		add_options_page( 'Setmore Plus', 'Setmore Plus', 'manage_options', 'setmoreplus', array( $this, 'options_page' ) );
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
			'<label for="setmoreplus_url">'. __( 'Your Booking Page URL', 'setmore-plus' ) . '</label>',
			array( $this, 'render_setting_url' ),
			'setmoreplus_group',
			'setmoreplus_section'
		);

		add_settings_field(
			'setmoreplus-staff-urls',
			'<label for="setmoreplus_staff_urls">'. __( 'Your Staff Booking Pages', 'setmore-plus' ) . '</label><br><em style="font-weight: 400">(optional)</em>',
			array( $this, 'render_setting_staff_urls' ),
			'setmoreplus_group',
			'setmoreplus_section'
		);

		add_settings_field(
			'setmoreplus-app-examples',
			'<label for="setmoreplus_app_examples">'. __( 'Scheduler Sizes', 'setmore-plus' ) . '</label>',
			array( $this, 'render_app_examples' ),
			'setmoreplus_group',
			'setmoreplus_section'
		);

		add_settings_field(
			'setmoreplus-popup-dimensions',
			'<label for="setmoreplus_popup_dimensions">'. __( 'Popup Dimensions', 'setmore-plus' ) . '</label>',
			array( $this, 'render_setting_popup_dimensions' ),
			'setmoreplus_group',
			'setmoreplus_section'
		);

		add_settings_field(
			'setmoreplus-embed-dimensions',
			'<label for="setmoreplus_embed_dimensions">'. __( 'Embed Dimensions', 'setmore-plus' ) . '</label>',
			array( $this, 'render_setting_embed_dimensions' ),
			'setmoreplus_group',
			'setmoreplus_section'
		);

		add_settings_field(
			'setmoreplus-defer',
			'<label for="setmoreplus_defer">' . __( 'Script Loading', 'setmore-plus' ) . '</label>',
			array( $this, 'render_setting_defer' ),
			'setmoreplus_group',
			'setmoreplus_section'
		);

		add_settings_field(
			'setmoreplus-lnt',
			'<label for="setmoreplus_lnt" class="lnt">' . __( 'Leave No Trace', 'setmore-plus' ) . '</label>',
			array( $this, 'render_setting_lnt' ),
			'setmoreplus_group',
			'setmoreplus_section'
		);

	}

	public function sanitize_options( $input ) {
		$new['url'] = sanitize_text_field( $input['url'] );
		if ( isset( $input['staff_urls'] ) ) {
			// Using independent counter because we cannot actually change the input value in the DOM.
			$i = 1;
			foreach ( $input['staff_urls'] as $id => $data ) {
				if ( $data['name'] && $data['url'] ) {
					$new['staff_urls'][ $i++ ] = array( 'name' => sanitize_text_field( $data['name'] ), 'url' => sanitize_text_field( $data['url'] ) );
				}
			}
		}
		else {
			$new['staff_urls'] = null;
		}
		$new['width']                      = sanitize_text_field( $input['width'] );
		$new['width_p']                    = sanitize_text_field( $input['width_p'] );
		$new['height']                     = sanitize_text_field( $input['height'] );
		$new['height_p']                   = sanitize_text_field( $input['height_p'] );
		$new['mobile_breakpoint']          = sanitize_text_field( $input['mobile_breakpoint'] );

		$new['embed_desktop_width']        = sanitize_text_field( $input['embed_desktop_width'] );
		$new['embed_desktop_width_p']      = sanitize_text_field( $input['embed_desktop_width_p'] );
		$new['embed_desktop_height']       = sanitize_text_field( $input['embed_desktop_height'] );

		$new['embed_mobile_breakpoint']    = sanitize_text_field( $input['embed_mobile_breakpoint'] );
		$new['embed_mobile_height']        = sanitize_text_field( $input['embed_mobile_height'] );

		$new['defer']                      = isset( $input['defer'] ) ? $input['defer'] : 1;
		$new['lnt']                        = isset( $input['lnt'] ) ? $input['lnt'] : 0;

		return $new;
	}

	public function render_setting_url() {
		$options = get_option( 'setmoreplus' );
		?>
		<input type="text" id="setmoreplus_url" name="setmoreplus[url]" style="width: 100%;" value="<?php echo $options['url']; ?>" placeholder="<?php _e( 'Setmore Booking Page URL', 'setmore-plus' ); ?>" />
		<p class="description">
			<?php printf( wp_kses( __( 'To find your unique URL, <a href="%s" target="_blank">sign in to Setmore</a> and click on <b>Profile</b>.', 'setmore-plus' ), array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ), 'b' => array(), ) ), 'http://my.setmore.com' ); ?>
		</p>
		<?php
	}

	public function render_setting_staff_urls() {
		$options = get_option( 'setmoreplus' );
		?>
		<div class="table-wrapper">
		<div id="staff-urls" class="table">
		<div class="row staff-header">
			<div class="cell staff-ids"><?php _e( 'ID' ); ?></div>
			<div class="cell staff-name"><?php _e( 'Staff Name', 'setmore-plus' ); ?></div>
			<div class="cell"><?php _e( 'Staff Booking Page URL', 'setmore-plus' ); ?></div>
			<div class="cell"></div>
		</div>
		<?php
		if ( $options['staff_urls'] ) {
			foreach ( $options['staff_urls'] as $id => $data ) {
				$this->render_staff_url_row( $id, $data['name'], $data['url'] );
			}
		}
		?>
		</div>
		</div>
		<p><input type="button" class="button" id="add-url" value="<?php _e( 'Add New', 'setmore-plus' ); ?>"></p>

		<p class="description">
			<?php printf( wp_kses( __( 'To find your individual Staff Booking Pages, <a href="%s" target="_blank">sign in to Setmore</a> and navigate to <b>Settings > Staff</b>.', 'setmore-plus' ), array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ), 'b' => array() ) ), esc_url( 'http://my.setmore.com' ) ); ?>
		</p>
		<?php
	}

	public function render_staff_url_row( $id, $name = '', $url = '' ) {
		?>
		<div class="row staff-row">
			<input type="hidden" class="original-order" value="<?php echo $id; ?>">
			<div class="cell">
				<div class="staff-id">
					<?php echo $id; ?>
				</div>
			</div>
			<div class="cell staff-name">
				<input type="text" name="setmoreplus[staff_urls][<?php echo $id; ?>][name]"
				       value="<?php echo $name; ?>" placeholder="<?php _e( 'staff name', 'setmore-plus' ); ?>">
			</div>
			<div class="cell staff-url">
				<input type="text" name="setmoreplus[staff_urls][<?php echo $id; ?>][url]"
				       value="<?php echo $url; ?>"
				       placeholder="<?php _e( 'Staff Booking Page', 'setmore-plus' ); ?>">
			</div>
			<div class="cell staff-delete"></div>
		</div>
		<?php
	}

	public function render_app_examples() {
		?>
		<p>The scheduler has three similar versions depending on the width of the popup or embed frame. <a id="openGallery" href="#">See examples</a>.</p>
		<table class="sizes">
			<tr>
				<th>Size</th>
				<th>Intended Use</th>
				<th>Width</th>
			</tr>
			<tr>
				<td>Small</td>
				<td>mobile devices</td>
				<td>less than 582 pixels, at least 320 pixels recommended</td>
			</tr>
			<tr>
				<td>Medium</td>
				<td>desktops</td>
				<td>at least 582 pixels</td>
			</tr>
			<tr>
				<td>Large</td>
				<td>desktop full-width pages</td>
				<td>at least 873 pixels</td>
			</tr>
		</table>
		<div style="display: none;">
			<a href="<?php echo SETMOREPLUS_IMAGES; ?>SetmorePlus-small.png" class="screenshot" title="<?php _e( 'Small', 'setmore-plus' ); ?>">Small</a>
			<a href="<?php echo SETMOREPLUS_IMAGES; ?>SetmorePlus-medium.png" class="screenshot" title="<?php _e( 'Medium', 'setmore-plus' ); ?>">Medium</a>
			<a href="<?php echo SETMOREPLUS_IMAGES; ?>SetmorePlus-large.png" class="screenshot" title="<?php _e( 'Large', 'setmore-plus' ); ?>">Large</a>
		</div>
		<?php
	}

	public function render_setting_popup_dimensions() {
		$options  = get_option( 'setmoreplus' );
		$defaults = $this->get_defaults();
		?>
		<p class="">When using <code>[setmoreplus link]</code> or <code>[setmoreplus button]</code>:</p>
		<div id="setmoreplus_popup_dimensions">
			<table class="dimensions">
				<tr>
					<td></td>
					<td>Width</td>
					<td>Height</td>
				</tr>
				<tr>
					<td>Desktop</td>
					<td>
						<label>
							<input id="setmoreplus_width" type="text" name="setmoreplus[width]"
							       value="<?php echo $options['width']; ?>"
							       data-default="<?php echo $defaults['width']; ?>"
							       data-current="<?php echo $options['width']; ?>"
							       class="four-digits next-to-select">
						</label>
						<label>
							<select name="setmoreplus[width_p]"
							        data-default="<?php echo $defaults['width_p']; ?>"
							        data-target="setmoreplus_width"
							        class="pxpct">
								<option value="px" <?php selected( $options['width_p'], 'px' ); ?>>pixels</option>
								<option value="%" <?php selected( $options['width_p'], '%' ); ?>>percent</option>
							</select>
						</label>
					</td>
					<td>
						<label>
							<input id="setmoreplus_height" type="text" name="setmoreplus[height]"
							       value="<?php echo $options['height']; ?>"
							       data-default="<?php echo $defaults['height']; ?>"
							       data-current="<?php echo $options['height']; ?>"
							       class="four-digits next-to-select">
						</label>
						<label>
							<select name="setmoreplus[height_p]"
							        data-default="<?php echo $defaults['height_p']; ?>"
							        data-target="setmoreplus_height"
							        class="pxpct">
								<option value="px" <?php selected( $options['height_p'], 'px' ); ?>>pixels</option>
								<option value="%" <?php selected( $options['height_p'], '%' ); ?>>percent</option>
							</select>
						</label>
					</td>
				<tr>
					<td>Mobile</td>
					<td colspan="2">
						<label>
							100% <b>screen</b> width and height below
							<input type="text" name="setmoreplus[mobile_breakpoint]"
						              value="<?php echo $options['mobile_breakpoint']; ?>"
						              data-default="<?php echo $defaults['mobile_breakpoint']; ?>"
						              class="four-digits"> pixels
						</label>
					</td>
				</tr>
			</table>
			<p><input type="button" class="button secondary restore-defaults" value="<?php _e( 'Restore Defaults', 'setmore-plus' ); ?>"></p>
		</div>
		<?php
	}

	public function render_setting_embed_dimensions() {
		$options  = get_option( 'setmoreplus' );
		$defaults = $this->get_defaults();
		?>
		<p>When using <code>[setmoreplus]</code> to embed the scheduler directly in a page:</p>
		<div id="setmoreplus_embed_dimensions">
			<table class="dimensions">
				<tr>
					<td></td>
					<td>Width</td>
					<td>Height</td>
				</tr>
				<tr>
					<td>Desktop</td>
					<td>
						<label>
							<input id="setmoreplus_embed_desktop_width" type="text" name="setmoreplus[embed_desktop_width]"
							       value="<?php echo $options['embed_desktop_width']; ?>"
							       data-default="<?php echo $defaults['embed_desktop_width']; ?>"
							       data-current="<?php echo $options['embed_desktop_width']; ?>"
							       class="four-digits next-to-select">
						</label>
						<label>
							<select name="setmoreplus[embed_desktop_width_p]"
							        data-default="<?php echo $defaults['embed_desktop_width_p']; ?>"
							        data-target="setmoreplus_embed_desktop_width"
							        class="pxpct">
								<option value="px" <?php selected( $options['embed_desktop_width_p'], 'px' ); ?>>pixels</option>
								<option value="%" <?php selected( $options['embed_desktop_width_p'], '%' ); ?>>percent</option>
							</select>
						</label>
					</td>
					<td>
						<label>
							<input id="setmoreplus_embed_desktop_height" type="text" name="setmoreplus[embed_desktop_height]"
							       value="<?php echo $options['embed_desktop_height']; ?>"
							       data-default="<?php echo $defaults['embed_desktop_height']; ?>"
							       data-current="<?php echo $options['embed_desktop_height']; ?>"
							       class="four-digits"> pixels
						</label>
					</td>
				</tr>
				<tr>
					<td>Mobile</td>
					<td>
						<label>
							100% <b>container</b> width below
							<input type="text" name="setmoreplus[embed_mobile_breakpoint]"
						              value="<?php echo $options['embed_mobile_breakpoint']; ?>"
						              data-default="<?php echo $defaults['embed_mobile_breakpoint']; ?>"
						              class="four-digits"> pixels
						</label>
					</td>
					<td>
						<label>
							<input id="setmoreplus_embed_mobile_height" type="text" name="setmoreplus[embed_mobile_height]"
							       value="<?php echo $options['embed_mobile_height']; ?>"
							       data-default="<?php echo $defaults['embed_mobile_height']; ?>"
							       class="four-digits"> pixels
						</label>
					</td>
				</tr>
			</table>
			<p><input type="button" class="button secondary restore-defaults" value="<?php _e( 'Restore Defaults', 'setmore-plus' ); ?>"></p>
		</div>
		<?php
	}

	public function render_setting_defer() {
		$options = get_option( 'setmoreplus' );
		?>
		<div id="defer">
			<select id="setmoreplus_defer" name="setmoreplus[defer]">
				<option value="1" <?php selected( $options['defer'], 1 ); ?>>
					<?php _e( 'Normal (default)', 'setmore-plus' ); ?>
				</option>
				<option value="0" <?php selected( $options['defer'], 0 ); ?>>
					<?php _e( 'Priority', 'setmore-plus' ); ?>
				</option>
			</select>

			<p class="description">
				<?php _e( '<strong>Normal</strong> works well in the majority of cases.', 'setmore-plus' ); ?>
				<?php _e( 'Try <strong>Priority</strong> if your Setmore link fails to produce a popup.', 'setmore-plus' ); ?>
			</p>
		</div>
		<?php
	}

	public function render_setting_lnt() {
		$options = get_option( 'setmoreplus' );
		?>
		<div id="leave-no-trace">
			<select id="setmoreplus_lnt" name="setmoreplus[lnt]">
				<option value="1" <?php selected( $options['lnt'], 1 ); ?>>
					<?php _e( 'Yes - Deleting this plugin will also delete these settings.', 'setmore-plus' ); ?>
				</option>
				<option value="0" <?php selected( $options['lnt'], 0 ); ?>>
					<?php _e( 'No - These settings will remain after deleting this plugin.', 'setmore-plus' ); ?>
				</option>
			</select>

			<p class="description">
				<?php _e( 'Deactivating this plugin will not delete anything.', 'setmore-plus' ); ?>
			</p>
		</div>
		<?php
	}

	public function options_page() {
		if ( ! current_user_can( 'manage_options' ) )
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

		?>
		<div class="wrap setmore">
			<h2><?php _e( 'Setmore Plus', 'setmore-plus' ); ?></h2>
			<?php
			$tab    = isset( $_GET['tab'] ) ? $_GET['tab'] : '';
			$screen = get_current_screen();
			$url    = add_query_arg( 'page', 'setmoreplus', admin_url( $screen->parent_file ) );
			?>
			<h2 class="nav-tab-wrapper">
				<a href="<?php echo $url; ?>" class="nav-tab <?php $this->is_active_tab( $tab, '' ); ?>">
					<?php _e( 'Settings', 'setmore-plus' ); ?>
				</a>
				<a href="<?php echo $url; ?>&tab=instructions" class="nav-tab <?php $this->is_active_tab( $tab, 'instructions' ); ?>">
					<?php _e( 'Instructions', 'setmore-plus' ); ?>
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

	public function is_active_tab( $tab, $nav_tab ) {
		echo $tab == $nav_tab ? 'nav-tab-active' : '';
	}

	public function register_widget() {
		register_widget( 'SetmorePlus_Widget' );
	}

	public function register_shortcodes() {
		add_shortcode( 'setmoreplus', array( $this, 'render_popup' ) );
	}

	public function render_popup( $atts, $content = '' ) {
		$options = get_option( 'setmoreplus' );

		extract( shortcode_atts(
			array(
				'button' => '',
				'link'   => '',
				'class'  => '',
				'staff'  => '',
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

		$url = apply_filters( 'setmoreplus_url', $options['url'], $staff );

		if ( $link ) {

			$html = sprintf( '<a class="%s" href="%s">%s</a>', $classes, $url, $content );

		}
		elseif ( $button ) {

			// href is not a valid attribute for <input> but Colorbox needs it to load the target page
			$html = sprintf( '<input type="button" class="%s" href="%s" value="%s" />', $classes, $url, $content );

		}
		else {

			// embed an iframe in the page
			$html = sprintf( '<iframe class="setmore-iframe" src="%s" width="%s" height="%s" frameborder="0"></iframe>',
				$url,
				$options['embed_desktop_width'] . ('%' == $options['embed_desktop_width_p'] ? '%' : '' ),
				$options['embed_desktop_height'] );

			$html .= '<style>@media only screen and (max-width: ' . $options['embed_mobile_breakpoint'] . 'px) { iframe.setmore-iframe { width: 100%; } }</style>';

		}
		return $html;
	}

	/**
	 * @param $url
	 * @param string $staff
	 * @since 4.0.0
	 *
	 * @return mixed
	 */
	public function url_filter( $url, $staff = '' ) {
		$options = get_option( 'setmoreplus' );
		if ( $staff ) {
			if ( is_numeric( $staff ) ) {
				if ( isset( $options['staff_urls'][ $staff ] ) && $options['staff_urls'][ $staff ] ) {
					$url = $options['staff_urls'][ $staff ]['url'];
				}
			}
			else {
				foreach ( $options['staff_urls'] as $staff_info ) {
					if ( strtolower( $staff ) == strtolower( $staff_info['name'] ) ) {
						$url = $staff_info['url'];
						break;
					}
				}
			}
		}
		return $url;
	}

	/**
	 * Display lightbox
	 *
	 * @since 2.3.0
	 */
	public function setmoreplus_script() {
		$options = get_option( 'setmoreplus' );
		$var = array(
			'iframe'      => true,
			'transition'  => 'elastic',
			'speed'       => 200,
			'height'      => $options['height'] . $options['height_p'],
			'width'       => $options['width'] . $options['width_p'],
			'breakpoint'  => $options['mobile_breakpoint'],
			'opacity'     => 0.8,
			'returnFocus' => false,
			'rel'         => false,
		);
		wp_localize_script( 'colorbox-script', 'setmoreplus', $var );
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
		if ( !empty( $atts ) ) {
			foreach ( $atts as $attribute => $value ) {
				if ( is_int( $attribute ) ) {
					$atts[ strtolower( $value ) ] = true;
					unset( $atts[ $attribute ] );
				}
			}
		}
		return $atts;
	}

	/**
	 * Show version number in <head> section.
	 *
	 * For troubleshooting only.
	 *
	 * @since 3.6.1
	 */
	function show_version_info() {
		global $wp_version;
		$plugin_info = $this->get_plugin_info();
		$comment = array(
			'WordPress ' . $wp_version,
			$plugin_info['name'] . ' ' . $plugin_info['version'],
		);

		echo "\n" . '<!-- versions: ' . implode( ' | ', $comment ) . ' -->' . "\n";
	}

	/**
	 * Return plugin info.
	 *
	 * @since 3.6.1
	 *
	 * @return array
	 */
	public function get_plugin_info() {
		return get_file_data( __FILE__, array( 'name' => 'Plugin Name', 'version' => 'Version' ) );
	}

}

new SetmorePlus();
