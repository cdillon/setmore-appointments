<?php if ( ! defined( 'ABSPATH' ) ) die; ?>

<div class="settings-page">
	<p>
        <?php printf(
            wp_kses(
                __( 'This plugin is offered by <a href="%s" target="_blank">WP Mission</a>. <em>We have no affiliation with <a href="%s" target="_blank">Setmore Appointments</a> and provide no technical support for their service.</em>', 'setmore-plus' ),
                array( 'em' => array(), 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ) )
            ),
            esc_url( 'https://www.wpmission.com' ), esc_url( 'https://setmore.com' ) ); ?>
    </p>

	<form method="post" action="options.php" autocomplete="off">
	<?php settings_fields( 'setmoreplus_group' ); ?>
	<?php do_settings_sections( 'setmoreplus_group' ); ?>
	<?php submit_button(); ?>
	</form>
</div>
