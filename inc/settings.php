<?php if ( ! defined( 'ABSPATH' ) ) die; ?>

<div class="settings-page">
	<p><?php printf( wp_kses( __( 'This plugin is offered by <a href="%s" target="_blank">WP Mission</a>. <em>We have no affiliation with <a href="%s" target="_blank">Setmore Appointments</a> and provide no technical support for their service.</em>', 'setmore-plus' ), array( 'em' => array(), 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ) ) ), esc_url( 'https://www.wpmission.com' ), esc_url( 'http://setmore.com' ) ); ?></p>

	<p><?php printf( wp_kses( __( 'We do, however, provide lifetime support for this plugin, including <a href="%s" target="_blank">free help</a> getting the <button class="demo">Book Appointment</button> button to match your theme.', 'setmore-plus' ), array( 'button' => array( 'class' => array() ), 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ) ) ), esc_url( 'https://www.wpmission.com/support' ) ); ?></p>

	<p><?php _e( 'If your site uses <strong>SSL</strong> (e.g. http<em class="hilite">s</em>://example.com) and the scheduler does not appear in the popup, try entering URLs without the <code>http:</code> like <code>//example.setmore.com</code>.', 'setmore-plus' ); ?></p>

	<form method="post" action="options.php" autocomplete="off">
	<?php settings_fields( 'setmoreplus_group' ); ?>
	<?php do_settings_sections( 'setmoreplus_group' ); ?>
	<?php submit_button(); ?>
	</form>
</div>
