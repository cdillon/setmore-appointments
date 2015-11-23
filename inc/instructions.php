<div class="wrap setmore">

	<h3>How to add SetMore to your site</h3>

	<p>To add a button in a <b>sidebar</b> that opens the scheduler in a popup, use the widget.</p>

	<p>To <b>embed</b> the scheduler directly in a page</p>
	<blockquote>[setmoreplus]</blockquote>

	<p>To add a <b>link</b> to the popup scheduler in a page</p>
	<blockquote>[setmoreplus link]</blockquote>

	<p>To add a <b>button</b> to the popup scheduler in a page</p>
	<blockquote>[setmoreplus button]</blockquote>

	<p><em>The link and the button will be styled by your theme.</em></p>

	<p>For a <b>menu link</b> (<a href="<?php echo dirname( plugin_dir_url( __FILE__ ) ); ?>/images/SetMorePlus-menu-link.png" class="colorbox">screenshot</a>)</p>

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

	<div id="menu-link-screenshot" style="display: none;"><img src="<?php echo dirname( plugin_dir_url( __FILE__ ) ); ?>/images/SetMorePlus-menu-link.png"></div>
	<ol>
		<li>go to Appearance > Menus</li>
		<li>click "Screen Options" in the upper right corner
		<li>if necessary, check the "Custom Links" and "CSS Classes" boxes</li>
		<li>add a custom link using your SetMore URL and link text</li>
		<li>enter <input type="text" readonly value="setmore-iframe" style="width: 130px; font-family: consolas, monospace;"> in the CSS Classes field</li>
	</ol>

	<h3>Customizing</h3>

	<p>With <code>link</code> or <code>button</code>, you can customize the link or button text</p>
	<blockquote>[setmoreplus button]Make an appointment today![/setmoreplus]</blockquote>

	<p>and/or add a custom CSS class from your theme</p>
	<blockquote>[setmoreplus button class="blue"]</blockquote>

	<p>The width and height shortcode attributes were removed in version 3.0. Use the settings instead.</p>
	<blockquote>[setmoreplus <span style="text-decoration: line-through;">width="800" height="650"</span>]</blockquote>

</div>
