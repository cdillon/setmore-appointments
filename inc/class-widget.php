<?php

class SetmorePlus_Widget extends WP_Widget {

	// Instantiate
	function __construct() {
		parent::__construct(
			'wpmsmp_widget',  // base ID
			__( 'SetMore Plus', 'setmore-plus' ),  // name
			array( 'description' => __( 'Add a "Book Appointment" button.', 'setmore-plus' ) )  // args
		);
	}

	// Output
	public function widget( $args, $instance ) {
		$options = get_option( 'setmoreplus' );

		// Load stylesheet and Colorbox, then localize script
		if ( 'link' == $instance['style'] ) {
			wp_enqueue_style( 'setmoreplus-widget-style', SETMOREPLUS_URL . 'css/widget.css' );
		}
		wp_enqueue_style( 'colorbox-style', SETMOREPLUS_URL . 'inc/colorbox/colorbox.css' );
		wp_enqueue_script( 'colorbox-script', SETMOREPLUS_URL . 'inc/colorbox/jquery.colorbox-min.js', array( 'jquery' ) );

		$var = array(
			'iframe'     => true,
			'transition' => 'elastic',
			'speed'      => 200,
			'height'     => $options['height'],
			'width'      => $options['width'],
			'opacity'    => 0.8,
		);
		wp_localize_script( 'colorbox-script', 'setmoreplus_widget', $var );
		add_action( 'wp_footer', array( $this, 'call_colorbox' ), 100 );

		// Build the widget
		$defaults = array( 'link-text' => __( 'Book Appointment', 'setmore-plus' ) );
		$data     = array_merge( $args, $instance );
		if ( empty( $data['link-text'] ) ) {
			$data['link-text'] = $defaults['link-text'];
		}

		echo $data['before_widget'];

		// widget title
		if ( ! empty( $data['title'] ) ) {
			echo $data['before_title'] . $data['title'] . $data['after_title'];
		}

		// widget text
		if ( ! empty( $data['text'] ) ) {
			echo '<p>' . $data['text'] . '</p>';
		}

		// widget link
		if ( 'button' == $data['style'] ) {
			?>
			<a class="setmore-iframe" href="<?php echo $options['url']; ?>">
				<img border="none" src="<?php echo SETMOREPLUS_URL . 'images/SetMore-book-button.png'; ?>" alt="Book an appointment"></a>
			<?php
		} elseif ( 'link' == $data['style'] ) {
			?>
			<a class="setmore setmore-iframe"
			   href="<?php echo $options['url']; ?>"><?php _e( $data['link-text'], 'setmore-plus' ); ?></a>
			<?php
		} else {
			?>
			<a class="setmore setmore-iframe"
			   href="<?php echo $options['url']; ?>"><?php _e( $data['link-text'], 'setmore-plus' ); ?></a>
			<?php
		}

		echo $data['after_widget'];
	}

	// Options form
	public function form( $instance ) {
		$defaults  = array(
			'title'     => __( '', 'setmore-plus' ),
			'text'      => __( '', 'setmore-plus' ),
			'link-text' => __( 'Book Appointment', 'setmore-plus' ),
			'style'     => 'button'
		);
		$instance  = wp_parse_args( (array) $instance, $defaults );
		$link_text = empty( $instance['link-text'] ) ? $defaults['link-text'] : $instance['link-text'];
		?>
		<script>
			// clicking demo buttons (1) selects radio button and (2) prevents link action
			jQuery(document).ready(function ($) {
				$("a.setmore-admin").click(function (e) {
					$(this).prev("input").attr("checked", "checked").focus();
					e.preventDefault();
				});
			});
		</script>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'setmore-plus' ); ?>:
				<em><?php _e( '(optional)', 'setmore-plus' ); ?></em></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" class="text widefat"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text', 'setmore-plus' ); ?>:
				<em><?php _e( '(optional)', 'setmore-plus' ); ?></em></label>
			<textarea id="<?php echo $this->get_field_id( 'text' ); ?>" class="text widefat"
			          name="<?php echo $this->get_field_name( 'text' ); ?>"
			          rows="3"><?php echo $instance['text']; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'link-text' ); ?>"><?php _e( 'Link Text', 'setmore-plus' ); ?>
				:</label>
			<input id="<?php echo $this->get_field_id( 'link-text' ); ?>" type="text" class="text widefat"
			       name="<?php echo $this->get_field_name( 'link-text' ); ?>"
			       value="<?php echo $instance['link-text']; ?>" placeholder="<?php echo $defaults['link-text']; ?>">
		</p>

		<?php _e( 'Style', 'setmore-plus' ); ?>:
		<ul class="setmore-style">
			<li>
				<label for="<?php echo $this->get_field_id( 'style-button' ); ?>">
					<input id="<?php echo $this->get_field_id( 'style-button' ); ?>" type="radio"
					       name="<?php echo $this->get_field_name( 'style' ); ?>"
					       value="button" <?php checked( $instance['style'], 'button' ); ?>>
					<a class="setmore-admin" href="#">
						<img style="vertical-align: middle;" border="none" src="<?php echo SETMOREPLUS_URL . 'images/SetMore-book-button.png'; ?>" alt="Book an appointment"></a>
				</label>
			</li>
			<li>
				<label for="<?php echo $this->get_field_id( 'style-link' ); ?>">
					<input id="<?php echo $this->get_field_id( 'style-link' ); ?>" type="radio"
					       name="<?php echo $this->get_field_name( 'style' ); ?>"
					       value="link" <?php checked( $instance['style'], 'link' ); ?>>
					<a class="setmore setmore-admin" href="#"><?php echo $link_text; ?></a>
				</label>
			</li>
			<li>
				<label for="<?php echo $this->get_field_id( 'style-none' ); ?>">
					<input id="<?php echo $this->get_field_id( 'style-none' ); ?>" type="radio"
					       name="<?php echo $this->get_field_name( 'style' ); ?>"
					       value="none" <?php checked( $instance['style'], 'none' ); ?>>
					<a class="setmore-admin" href="#"><?php echo $link_text; ?></a>
				</label>

				<p><?php _e( "Unstyled. Add style to <code>a.setmore</code> in your theme's stylesheet.", 'setmore-plus' ); ?></p>
			</li>
		</ul>
		<?php
	}

	// Save settings
	public function update( $new_instance, $old_instance ) {
		$instance              = $old_instance;
		$instance['title']     = strip_tags( $new_instance['title'] );
		$instance['text']      = strip_tags( $new_instance['text'] );
		$instance['link-text'] = strip_tags( $new_instance['link-text'] );
		$instance['style']     = $new_instance['style'];

		return $instance;
	}

	// Script to call lightbox
	public function call_colorbox() {
		?>
		<!-- SetMore Plus plugin -->
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$(".widget .setmore-iframe").colorbox(setmoreplus_widget);
			});
		</script>
		<?php
	}

}
	