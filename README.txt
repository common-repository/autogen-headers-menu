=== Autogen Headers Menu ===
Contributors: amirshk
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Z3W2VRXFHK2KY
Tags: headers, menu, plugin, post, auto, generate, widget, main-menu
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 1.0.1
License: GPLv2 or later

Automatically create a main-menu from the headers in your post using a simple and configurable shortcode.

== Description ==

This plugin will automatically generate an ordered menu from the headers in your post.

1. The plugin adds an ID to all your headers in the post.
2. Add the shortcode &#91;autogen_menu&#93; anywhere in the post, to automatically generate a menu with links to your post's headers.
3. You have the option to add links the top of the page next to all the headers. Do this by adding the custom field "autogen_menu_show_top" with a value "true" to the relevant posts.

= "autogen_menu" Shortcode Options =

Options for the &#91;autogen_menu&#93; shortcode:

* head_class = style classes for the `Menu` header.
* div_class = style classes for the wrapping div.
* ol_class = style classes for all `OL`.
* li_class = style classes for all `LI`.
* a_class = style classes for all the links.
* depth = the maximum levels of menu to display. The default is 3.

= Custom Fields =

1. Add the custom field "autogen_menu_show_top" with a value "true" to add "top" links next to your headers.
2. Add "autogen_menu_show_numbering" to add numbers to all your headers.

= Widgets =

1. There is a new widget called 'Autogen Menu Widget' that displays the post's generated menu.

== Installation ==

1. Upload the "autogen-headers-menu" plugin to your `/wp-content/plugins/` directory.
2. Activate it.

== Frequently Asked Questions ==
Question: Will this plugin change my posts?

Answer: The only thing you add to your post is the shortcode. The plugin renders on display and changes nothing in the saved content.

== Screenshots ==

1. An example of the menu created.

== Changelog ==

= 1.0.1 =

1. Added a menu widget.

= 1.0.0 =

1. Added the option to have numbering to the headers.
2. Added the option to ignore a specific header (usefull when placing the Menu after some content).
3. Added the option to set the menu's header text

= 0.9.2 =

1. Made the plugin translation ready.
2. Added the option to have links to the top of the page next to headers.
3. Added "depth" option.
4. Fixed a bug while displaying a menu with depth larger than 2.


= 0.9.0 =
First public version of the plugin.
