=== SetMore Plus ===
Contributors: cdillon27
Tags: appointments, book, booking, calendar, free, online, salon, spa, schedule, scheduling
Requires at least: 3.3
Tested up to: 3.9.1
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Let your customers book appointments directly on your website using SetMore Appointments.

== Description ==

SetMore Plus by [WPMission](http://www.wpmission.com) may not be the official plugin for [SetMore Appointments](http://setmore.com) but my clients like it better :)

= About SetMore Appointments =

SetMore helps you manage appointments, schedules and customers, all through an easy-to-use web application. Your customers can book online, and pick their favorite staff, service and time slot without picking up the phone. Learn more at http://setmore.com.

= Getting Started with a Free Account =

Signing up is easy and fast with no credit card needed. Once you have an account, you are ready to take appointments. To get a free SetMore account simply visit http://setmore.com.

== Installation ==

* Upload `/setmore-plus` to the `/wp-content/plugins/` directory.

or

* Search for "SetMore Plus" on your `Plugins > Add New` page.

then

1. Activate the plugin.
1. Go to `Settings > SetMore Plus`.
1. In another browser tab, sign in to [my.setmore.com](http://my.setmore.com).
1. Copy your Booking Page URL from your "Profile" tab.
1. Paste that URL into the `SetMore Booking URL` field in WordPress. Remember to "Save Changes".
1. Use the widget to add a "Book Appointment" button to a sidebar, or use the shortcode `[setmore_iframe]` to add the scheduler to a page.

== Frequently Asked Questions ==

= How do I get a SetMore account? =

Visit [SetMore](http://setmore.com) to get your free account. A [premium plan](http://www.setmore.com/premium) with more features is also available.

= How do I change the "Book Appointment" button?

In the widget, you can select the default image button, a trendy flat button, or a plain link. To create a custom button, select the plain link option, then add style rules for `a.setmore` in your theme's stylesheet or custom CSS function. Here's an example of a square blue button with white text:
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

== Changelog ==

= 2.0 =
* Forked from SetMore Appointments 1.0.
* Updated for WordPress 3.9. New minimum version 3.3.
* Improved widget options.
* New shortcode to add an iframe to a page.
* Using Colorbox for iframe lightbox.
* Ready for internationalization (i18n).

= 1.0 =
* This is the first version.

== Upgrade Notice ==

= 2.0 =
Updated for WordPress 3.9. Improved widget options.
