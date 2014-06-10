<?php
/*
	Plugin Name: SetMore Appointments
	Plugin URI: http://setmore.com/
	Description: SetMore Appointments – Take customer appointments online for free.
	Version: 2.0
	Author: Chris Dillon
	Author URI: http://www.wpmission.com
	Forked From: version 1.0 by David Raffauf of SetMore Appointments at http://setmore.com
	Text Domain: setmore-appointments
	Requires: 3.3 or higher
	License: GPLv3 or later


  Copyright 2014  Chris Dillon  chris@wpmission.com

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define( 'SETMORE_NAME', 'setmore-appointments' );

/*----------------------------------------------------------------------------*
 * Activation / Deactivation Hooks
 *----------------------------------------------------------------------------*/

register_activation_hook( __FILE__, 'setmore_install' );
register_deactivation_hook( __FILE__, 'setmore_remove' );

function setmore_install() {
	add_option( 'setmore_booking_page_url', '' );
}

function setmore_remove() {
	delete_option( 'setmore_booking_page_url' );
}

// Add "Settings" link to plugin admin list
function setmore_action_links( $links, $file ) {
	$this_plugin = plugin_basename( __FILE__ );

	if ( $file == $this_plugin ){
		$settings_link = '<a href="options-general.php?page=setmore">' . __( 'Settings', SETMORE_NAME ) . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_filter( 'plugin_action_links', 'setmore_action_links', 10, 2 );

// Create an admin menu and register settings
if ( is_admin() ) {
	function setmore_admin_menu() {
		add_options_page('SetMore Appointments Options', 'SetMore', 'administrator', 'setmore', 'setmore_settings_page');
	}
	add_action( 'admin_menu', 'setmore_admin_menu' );
	add_action( 'admin_init', 'setmore_register_settings' );
}

function setmore_register_settings() {
	register_setting( 'setmore-settings-group', 'setmore_booking_page_url', 'setmore_sanitize_options' );
}

function setmore_sanitize_options( $input ) {
	// If multiple settings:
	// $input['setmore_booking_page_url'] = sanitize_text_field( $input['setmore_booking_page_url'] );
	
	// If single setting:
	$input = sanitize_text_field( $input );
	
	return $input;
}

/*----------------------------------------------------------------------------*
 * Register styles and scripts
 *----------------------------------------------------------------------------*/

// Front-end 
function setmore_scripts() {
	// Only register here. Enqueue them if widget is active.
	wp_register_style( 'setmore-widget-style', plugins_url( 'css/widget.css', __FILE__ ) );
  wp_register_style( 'colorbox-style', plugins_url( 'colorbox/colorbox.css', __FILE__ ) );
	wp_register_script( 'colorbox-script', plugins_url( 'colorbox/jquery.colorbox-min.js', __FILE__ ), array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'setmore_scripts' );

// Back-end
function setmore_admin_scripts( $hook ) {
	// Only load these on the widgets page.
	if ( 'widgets.php' == $hook ) {
		wp_enqueue_style( 'setmore-widget-style', plugins_url( 'css/widget.css', __FILE__ ) );
		wp_enqueue_style( 'setmore-widget-admin', plugins_url( 'css/admin.css', __FILE__ ) );
	}
}
add_action( 'admin_enqueue_scripts', 'setmore_admin_scripts' );

/*----------------------------------------------------------------------------*
 * Widget
 *----------------------------------------------------------------------------*/

class Setmore_Widget extends WP_Widget {

	// Instantiate
	function __construct() {
		parent::__construct(
			'setmore_widget',  // base ID
			__( 'SetMore', SETMORE_NAME ),  // name
			array( 'description' => __( 'Add a "Book Appointment" button.', SETMORE_NAME ) )  // args
		);
	}
		
	// Output
	public function widget( $args, $instance ) {
		if ( is_active_widget( false, false, $this->id_base ) ) {
			// Load stylesheet and Colorbox
			wp_enqueue_style( 'setmore-widget-style' );
			wp_enqueue_style( 'colorbox-style');
			wp_enqueue_script( 'colorbox-script' );
			// Colorbox caller
			add_action( 'wp_footer', 'setmore_widget_script', 50 );
		}
		
		$setmore_url = get_option('setmore_booking_page_url');
		$defaults = array( 'link-text' => __( 'Book Appointment', SETMORE_NAME) );
		$data = array_merge( $args, $instance );
		if ( empty( $data['link-text'] ) )
			$data['link-text'] = $defaults['link-text'];
		
		echo $data['before_widget'];
		
		// widget title
		if ( ! empty( $data['title'] ) )
			echo $data['before_title'] . $data['title'] . $data['after_title'];
		
		// widget text
		if ( ! empty( $data['text'] ) )
			echo '<p>' . $data['text'] . '</p>';
		
		// widget link
		if ( 'button' == $data['style'] ) {
			?>
			<a class="iframe" href="<?php echo $setmore_url; ?>"><img border="none" src="http://my.setmore.com/images/bookappt/SetMore-book-button.png" alt="Book an appointment"></a>
			<?php
		} elseif( 'link' == $data['style'] ) {
			?>
			<a class="setmore iframe" href="<?php echo $setmore_url; ?>"><?php _e( $data['link-text'], SETMORE_NAME ); ?></a>
			<?php
		} else {
			?>
			<a class="iframe" href="<?php echo $setmore_url; ?>"><?php _e( $data['link-text'], SETMORE_NAME ); ?></a>
			<?php
		}
		
		echo $data['after_widget'];
	}

	// Options form
	public function form( $instance ) {
		$defaults = array( 
				'title'     => __( '', SETMORE_NAME ), 
				'text'      => __( '', SETMORE_NAME ),
				'link-text' => __( 'Book Appointment', SETMORE_NAME ),
				'style'     => 'button'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$link_text = empty( $instance['link-text'] ) ? $defaults['link-text'] : $instance['link-text'];
		?>
		<script>
			// clicking demo buttons selects radio button + prevent link action
			jQuery(document).ready(function($) { 
				$("a.setmore-admin").click(function(e){ 
					$(this).prev("input").attr("checked", "checked").focus();
					e.preventDefault();
				}); 
			});
		</script>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', SETMORE_NAME ); ?>: <em><?php _e( '(optional)', SETMORE_NAME ); ?></em></label>
			<input 
				id="<?php echo $this->get_field_id( 'title' ); ?>" 
				type="text" 
				class="text widefat" 
				name="<?php echo $this->get_field_name('title'); ?>" 
				value="<?php echo $instance['title']; ?>">
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text', SETMORE_NAME ); ?>: <em><?php _e( '(optional)', SETMORE_NAME ); ?></em></label>
			<textarea 
				id="<?php echo $this->get_field_id( 'text' ); ?>" 
				class="text widefat" 
				name="<?php echo $this->get_field_name('text'); ?>" 
				rows="3"><?php echo $instance['text']; ?></textarea>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'link-text' ); ?>"><?php _e( 'Link Text', SETMORE_NAME ); ?>:</label>
			<input 
				id="<?php echo $this->get_field_id( 'link-text' ); ?>" 
				type="text" 
				class="text widefat" 
				name="<?php echo $this->get_field_name('link-text'); ?>" 
				value="<?php echo $instance['link-text']; ?>" 
				placeholder="<?php echo $defaults['link-text']; ?>">
		</p>
		
		<?php _e( 'Style', SETMORE_NAME ); ?>:
		<ul class="setmore-style">
			<li>
				<label for="<?php echo $this->get_field_id( 'style-button' ); ?>">
					<input
						id="<?php echo $this->get_field_id( 'style-button' ); ?>"
						type="radio"
						name="<?php echo $this->get_field_name( 'style' ); ?>"
						value="button"
						<?php checked( $instance['style'], 'button' ); ?>>
					<a class="setmore-admin" href="#"><img style="vertical-align: middle;" border="none" src="http://my.setmore.com/images/bookappt/SetMore-book-button.png" alt="Book an appointment"></a>
				</label>
			</li>
			<li>
				<label for="<?php echo $this->get_field_id( 'style-link' ); ?>">
					<input 
						id="<?php echo $this->get_field_id( 'style-link' ); ?>"
						type="radio"
						name="<?php echo $this->get_field_name( 'style' ); ?>"
						value="link"
						<?php checked( $instance['style'], 'link' ); ?>>
					<a class="setmore setmore-admin" href="#"><?php echo $link_text; ?></a>
				</label>
			</li>
			<li>
				<label for="<?php echo $this->get_field_id( 'style-none' ); ?>">
					<input 
						id="<?php echo $this->get_field_id( 'style-none' ); ?>"
						type="radio"
						name="<?php echo $this->get_field_name( 'style' ); ?>"
						value="none"
						<?php checked( $instance['style'], 'none' ); ?>>
						<a class="setmore-admin" href="#"><?php echo $link_text; ?></a>
				</label>
				<p><?php _e( "Unstyled. Add style to <code>a.setmore</code> in your theme's stylesheet.", SETMORE_NAME ); ?></p>
			</li>
		</ul>
		<?php
	}

	// Save settings
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']     = strip_tags( $new_instance['title'] );
		$instance['text']      = strip_tags( $new_instance['text'] );
		$instance['link-text'] = strip_tags( $new_instance['link-text'] );
		$instance['style']     = $new_instance['style'];
		
		return $instance;
	}

}

function setmore_register_widget() {
	register_widget( 'Setmore_Widget' );
}
add_action( 'widgets_init', 'setmore_register_widget' );

// Script to call lightbox
function setmore_widget_script() {
	?>
	<script>
	jQuery(document).ready(function($) { 
		$(".iframe").colorbox({
			'iframe'     : true,
			'transition' : 'elastic',
			'speed'      : 200,
			'height'		 : 680,
			'width'			 : 540,
			'opacity'    : 0.8,
		});
	});
	</script>
	<?php
}

/*----------------------------------------------------------------------------*
 * Settings
 *----------------------------------------------------------------------------*/

function setmore_settings_page() {
	?>
	<div class="wrap">
	
		<h2>SetMore Appointments Options</h2>

		<form method="post" action="options.php">
			<?php settings_fields( 'setmore-settings-group' ); ?>
			<?php do_settings_sections( 'setmore-settings-group' ); ?>
			
			<table>
				<tr valign="" align="left">
					<th width="190" scope="row">Your SetMore Booking URL</th>
					<td width="480">				
						<input 
							type="text"  
							id="setmore_booking_page_url"
							name="setmore_booking_page_url" 
							style="width: 310px;"
							value="<?php echo get_option('setmore_booking_page_url'); ?>"
							placeholder="SetMore Booking Page URL">
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>

		<h3>Shortcode</h3>
		
		<p><code>[setmore_iframe]</code> will place the SetMore scheduler on a page.</p>
		
		<h3>Where can I find my Booking Page URL?</h3>

		<p>
			<a href="http://my.setmore.com" target="_blank">Sign into SetMore</a> and click on the Profile tab. Use the default Booking Page URL or customize it.
		</p>
		
		<h3>Don't have a SetMore account? No problem!</h3>

		<p>
			Signing up with SetMore is simple and you can even 
			<a href="http://www.setmore.com" target="_blank">get started with a completely free account</a>.			
		</p>
		
	</div>
	<?php
}

/*----------------------------------------------------------------------------*
 * Shortcodes
 *----------------------------------------------------------------------------*/

// Iframe in a page
function setmore_iframe_function() {
	$html = '<iframe src="' . get_option( 'setmore_booking_page_url' ) . '" width="600" height="750" frameborder="0"></iframe>';
  return $html;
}

// Register shortcodes
function setmore_register_shortcodes() {
  add_shortcode( 'setmore_iframe', 'setmore_iframe_function' );
}
add_action( 'init', 'setmore_register_shortcodes' );
