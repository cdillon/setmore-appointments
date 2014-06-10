=== SetMore Appointments ===
Contributors: cdillon27, davidfull
Tags: appointments, book, booking, calendar, free, online, salon, spa, schedule, scheduling
Requires at least: 3.3
Tested up to: 3.9.1
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Let your customers book appointments directly on your website using SetMore Appointments.

== Description ==

= About SetMore Appointments =

SetMore helps you manage appointments, schedules and customers, all through an easy to use use web application. Your customers can book online, and pick their favorite staff, service and time-slot without picking up the phone. Learn more at http://setmore.com.

= Getting Started with a Free Account =

Signing up is easy, fast and you don't need a credit card to get started. Once you have an account you'll be ready to take appointments. To get a free SetMore account simply visit http://setmore.com.

== Installation ==

* Upload `/setmore-appointments` to the `/wp-content/plugins/` directory.

or

* Search for "SetMore Appointments" on your `Plugins > Add New` page.

then

1. Activate the plugin.
1. Go to `Settings > SetMore`.
1. In another browser tab, sign in to [my.setmore.com](http://my.setmore.com).
1. Copy your Booking Page URL from your "Profile" tab.
1. Paste that URL in the `SetMore Appointments Options` field in WordPress. Remember to "Save Changes".
1. Use the widget to add a "Book Appointment" button to a sidebar, or use the shortcode `[setmore_iframe]` to add the scheduler to a page.

== Frequently Asked Questions ==

= How do I get a SetMore account? =

Visit [SetMore](http://setmore.com) to get your free account. We also offer a [premium plan](http://www.setmore.com/premium) with more features.

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

== Screenshots ==

1. With the SetMore widget, you will see the "Book Appointment" button in your sidebar.
2. Clicking the "Book Appointment" button will display the process for booking an appointment.
3. Once you've added the folder containing the SetMore plugin to your plugins folder, the plugin will appear in WordPress' Plugins page.  Click "activate" to get started.
4. Under the settings menu, you will now see a SetMore link. Click this link to set up the plugin.
5. Within the "Available Widgets" area you will see a SetMore widget.
6. Drag this widget to Main Sidebar on the right, or into whichever sidebar you'd like it included within. Now your "Book Appointment" button will display in your website's sidebar.

== Changelog ==

= 2.0 =
* Updated for WordPress 3.9 and new minimum version 3.3.
* Improved widget options.
* Added shortcode to add an iframe to a page.
* Using Colorbox for iframe lightbox.
* Ready for internationalization (i18n).

= 1.0 =
* This is the first version.

== Upgrade Notice ==

= 2.0 =
Updated for WordPress 3.9. Improved widget options.
