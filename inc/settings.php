<div class="settings-page">
	<p>This plugin is offered by <a href="https://www.wpmission.com" target="_blank">WP Mission</a>. <em>We have no affiliation with SetMore Appointments and provide no technical support for their service.</em></p>
	<p>We do, however, provide lifetime support for this plugin, including <a href="https://www.wpmission.com/contact" target="_blank">free help</a> getting the <button class="demo">Book Appointment</button> button to match your theme.</p>

	<form method="post" action="options.php">
	<?php settings_fields( 'setmoreplus_group' ); ?>
	<?php do_settings_sections( 'setmoreplus_group' ); ?>
	<?php submit_button(); ?>
	</form>
</div>
