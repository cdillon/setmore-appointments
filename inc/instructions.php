<?php if ( ! defined( 'ABSPATH' ) ) die; ?>

<div class="wrap setmore">

	<h3><?php _e( 'How to add Setmore to your site', 'setmore-plus' ); ?></h3>

	<table class="setmore-instructions">
		<tbody>
		<tr>
			<td><p><?php _e( 'To add a button in a <b>sidebar</b> that opens the scheduler in a popup', 'setmore-plus' ); ?></p></td>
			<td><p><?php _e( 'widget' ); ?></p></td>
		</tr>
		<tr>
			<td><p><?php _e( 'To <b>embed</b> the scheduler directly in a page', 'setmore-plus' ); ?></p></td>
			<td class="code">[setmoreplus]</td>
		</tr>
		<tr>
			<td>
				<p><?php _e( 'To add a <b>link</b> to the popup scheduler in a page', 'setmore-plus' ); ?></p>
				<p><em><?php _e( 'The link will be styled by your theme.', 'setmore-plus' ); ?></em></p>
			</td>
			<td class="code">[setmoreplus link]</td>
		</tr>
		<tr>
			<td>
				<p><?php _e( 'To add a <b>button</b> to the popup scheduler in a page', 'setmore-plus' ); ?></p>
				<p><em><?php _e( 'The button will be styled by your theme.', 'setmore-plus' ); ?></em></p>
			</td>
			<td class="code">[setmoreplus button]</td>
		</tr>
		<tr>
			<td><p><?php _e( 'To select a Staff Booking Page', 'setmore-plus' ); ?></p></td>
			<td class="code">
				[setmoreplus staff=1]
				<br>
				[setmoreplus staff="Chris"]
			</td>
		</tr>
		<tr>
			<td><p><?php _e( 'For a <b>menu link</b>', 'setmore-plus' ); ?>
				(<a href="<?php echo dirname( plugin_dir_url( __FILE__ ) ); ?>/images/SetmorePlus-menu-link.png"
			    class="colorbox"><?php _e( 'screenshot', 'setmore-plus' ); ?></a>)</p>
				<div id="menu-link-screenshot" style="display: none;"><img src="<?php echo dirname( plugin_dir_url( __FILE__ ) ); ?>/images/SetmorePlus-menu-link.png"></div>
			</td>
			<td>
				<ol>
					<li><?php _e( 'go to Appearance > Menus', 'setmore-plus' ); ?></li>
					<li><?php _e( 'click "Screen Options" in the upper right corner', 'setmore-plus' ); ?>
					<li><?php _e( 'if necessary, check the "Custom Links" and "CSS Classes" boxes', 'setmore-plus' ); ?></li>
					<li><?php _e( 'add a custom link using your Setmore URL and link text', 'setmore-plus' ); ?></li>
					<li><?php printf( __( 'enter %s in the CSS Classes field', 'setmore-plus'), '<input type="text" readonly value="setmore-iframe" style="width: 130px; font-family: consolas, monospace;">' ); ?></li>
				</ol>
			</td>

		</tr>
		</tbody>
	</table>

	<h3>Customizing</h3>

	<table class="setmore-instructions">
		<tbody>
		<tr>
			<td><p><?php _e( 'With <code>link</code> or <code>button</code>, you can customize the text', 'setmore-plus' ); ?></p></td>
			<td class="code">[setmoreplus button]<? _e( 'Make an appointment today!', 'setmore-plus' ); ?>[/setmoreplus]</td>
		</tr>
		<tr>
			<td><p><?php _e( 'and/or add a CSS class from your theme or custom CSS.', 'setmore-plus' ); ?></p></td>
			<td class="code">
				[setmoreplus button class="blue"]
				<br>
				[setmoreplus link class="red"]
			</td>
		</tr>
		<tr>
			<td><p><?php _e( 'The width and height shortcode attributes were removed.<br>Use the settings instead.', 'setmore-plus' ); ?></p></td>
			<td class="code">[setmoreplus <span style="text-decoration: line-through;">width="800" height="650"</span>]</td>
		</tr>
		</tbody>
	</table>

</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("a.colorbox").colorbox({
			'iframe': true,
			'transition': 'elastic',
			'title': 'screenshot',
			'speed': 200,
			'opacity': 0.8,
			'height': 830 * 0.9,
			'width': 1058 * 0.9,
			'returnFocus': false,
			'rel': false
		});
	});
</script>
