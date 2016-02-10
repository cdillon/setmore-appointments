=== Setmore Plus ===
Contributors: cdillon27
Donate link: https://www.wpmission.com/donate/
Tags: appointments, book, booking, calendar, salon, spa, schedule, scheduling
Requires at least: 3.5
Tested up to: 4.4.2
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Allow your customers to make appointments online using the setmore.com service. Now works with individual staff booking pages.

== Description ==

Setmore Plus by [WP Mission](https://www.wpmission.com) may not be the official plugin for [Setmore Appointments](http://setmore.com) but my clients like it better :)

**[See demos of various options here!](http://demos.wpmission.com/setmore-plus/)**

= About Setmore Appointments =

Setmore helps you manage appointments, schedules, and customers, all through an easy-to-use web application. Your customers can book online, and pick their favorite staff, service and time-slot without picking up the phone.

= Getting started with a free account =

Signing up is easy, fast and you don't need a credit card to get started. Once you have an account you'll be ready to take appointments.

Learn more and get a free Setmore account at [Setmore.com](http://setmore.com).

= Add Setmore to your site =

After entering your main booking page URL provided by Setmore, to display the scheduler on your site:
* use a **widget** to place a "Book Appointments" button in a sidebar area
* use the **shortcode**
    * to embed the Setmore scheduler directly in a page
    * or add a link or a button that opens the scheduler in a popup window
* create a **menu link** that opens the scheduler popup

The widget and shortcode also work with individual staff booking pages. Full instructions are included.

*This plugin is offered by [WP Mission](https://www.wpmission.com). We have no affiliation with Setmore Appointments and provide no technical support for their service.*

We do, however, provide lifetime support for this plugin, including free help getting the "Book Appointment" button to match your theme.

This plugin can *leave no trace!* If you delete the plugin, all settings will be removed from the database. Guaranteed. However, simply deactivating it will leave your settings in place, as expected.

= Recommended =

* [Modular Custom CSS](https://wordpress.org/plugins/modular-custom-css/) for quick CSS tweaks right there in the Customizer.
* [Wider Admin Menu](http://wordpress.org/plugins/wider-admin-menu/) lets your admin menu b r e a t h e.

= Translations =

* Spanish (es_ES) - Richy Canello

Did you know you can help by [translating phrases here](https://translate.wordpress.org/projects/wp-plugins/setmore-plus)? Even just a few phrases would help.

[Contact me](https://www.wpmission.com/contact/) to contribute a full translation.

== Installation ==

1. Go to `Plugins > Add New`.
1. Search for "setmore plus".
1. Click "Install Now".

OR

1. Download the zip file.
1. Upload the zip file via `Plugins > Add New > Upload`.

Finally, activate the plugin.

1. Go to `Settings > Setmore Plus`.
1. In another browser tab, sign in to [my.setmore.com](http://my.setmore.com).
1. Copy your Booking Page URL from your "Profile" tab.
1. Paste that URL into the `Setmore Booking URL` field in WordPress.
1. Add the widget to a sidebar, the shortcode to a page, or a custom link to a menu.

Need help? Use the [support forum](http://wordpress.org/support/plugin/strong-testimonials) or [contact me](https://www.wpmission.com/contact/).


== Frequently Asked Questions ==

= How do I get a Setmore account? =

Visit [Setmore](http://setmore.com) to get your free account. A [premium plan](http://www.setmore.com/premium) with more features is also available.

= How do I change the "Book Appointment" button? =

In the widget, you can select the default image button, a trendy flat button, or a plain link.

To create a custom button, select the plain link option, then add style rules for `a.setmore` in your theme's stylesheet or custom CSS function, or try [Modular Custom CSS](https://wordpress.org/plugins/modular-custom-css/).

For example, here's a square blue button with white text:
`
a.setmore {
	background: #4372AA;
	color: #eee;
	display: inline-block;
	margin: 10px 0;
	padding: 10px 20px;
	text-decoration: none;
	text-shadow: 1px 1px 1px rgba(0,0,0,0.5);
}

a.setmore:hover {
	background: #769CC9;
	color: #fff;
	text-decoration: none;
}
`

Need help? Use the [support forum](http://wordpress.org/support/plugin/setmore-plus) or [contact me](https://www.wpmission.com/support).

= Leave no trace? What's that about? =

Some plugins and themes don't fully uninstall everything they installed - things like settings, database tables, subdirectories. That bugs me. Sometimes, it bugs your WordPress too.

So this plugin has an option to completely remove itself upon deletion. Simply deactivating the plugin will leave the settings intact.


== Changelog ==

= 3.4 =
* Add individual staff booking pages to shortcode and widget.
* Spanish translation.

= 3.3 =
* Add option to load scripts in header instead of footer.

= 3.2 =
* Fix bug when using shortcode and widget on same page.

= 3.1 =
* Fix bug in iframe.

= 3.0 =
* Add option for a menu link to the popup.
* Remove shortcode width & height attributes.
* Improve admin screen.

= 2.3 =
* More shortcode options.

= 2.2.2 =
* Add filter to exempt shortcode from wptexturize in WordPress 4.0.1+.

= 2.2.1 =
* Fix bug in shortcode.

= 2.2 =
* Added "Leave No Trace" feature.
* Added uninstall.php, a best practice.
* Object-oriented refactor.

= 2.1 =
* Improved settings page.

= 2.0 =
* Forked from Setmore Appointments 1.0.
* Updated for WordPress 3.9. New minimum version 3.3.
* Improved widget options.
* New shortcode to add an iframe to a page.
* Using Colorbox for iframe lightbox.
* Ready for internationalization (i18n).

= 1.0 =
* This is the first version.

== Upgrade Notice ==

= 3.4 =
Now offers individual staff booking pages. New Spanish translation.
