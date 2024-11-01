=== Vimeotheque Debug ===
Contributors: codeflavors, constantin.boiangiu
Tags: debug, vimeotheque
Requires at least: 5.2
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Debug add-on for plugin Vimeotheque by CodeFlavors

== Description ==

**Vimeotheque Debug** is an add-on for WordPress plugin [Vimeotheque](https://wordpress.org/plugins/codeflavors-vimeo-video-post-lite/) which logs various debug messages emitted by Vimeotheque when certain actions are taken (ie. video importing, video queries, featured image imports and so on).

The plugin will add a new menu item called "Debug log" under Vimeotheque's admin menu which will point to the debug page that shows the latest messages emitted by Vimeotheque.

The debugger requires plugin Vimeotheque 2.0 (or above) to be activated in order to capture the messages.

== Changelog ==
= 1.0.1 =
* Solved bug that generated PHP notice in Debug page if Vimeotheque PRO could not be found.

= 1.0 =

* Initial release.