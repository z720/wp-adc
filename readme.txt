=== Author Default Category ===
Contributors: z720
Tags: category, post, xmlrpc
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.0

Any author (user can edit posts) can set its own default category on his profil page.

== Description ==

A basic default category plugin. 
The main purpose of this plugin is to provide different default categories on a per user basis.

I was looking for a plugin to set a different default category for my flickr posts (through XMLRPC API). 
And there was no built-in function to set a default category to a particular user.

This plugin is very simple :

* any author can set it's own default category (user can `edit_posts`)
* any admin can modify an author default category (user can `edit_user`)
* anywhere in WordPress a user get it's default category where applicable : posts, API posts, … except in the writing settings page

== Frequently Asked Questions ==

It doesn't work?

1. Fill an issue on Github : https://github.com/z720/wp-adc/issues/new
1. Fix it or contribute on Github : https://github.com/z720/wp-adc/
1. Contact me : http://z720.net/contact


== Installation ==

1. Upload `wp-adc.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


== Screenshots ==

1. User profile with default category selection

== Changelog ==

= 1.0 =
* Initial version