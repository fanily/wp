=== Tagged User Notification ===
Contributors: jstnlngls
Tags: comments, tagging, user notification, notification, replies, multi-author
Requires at least: 2.0.2
Tested up to: 3.9.1. 
Stable tag: 2

This plugin allows you to tag other registered users on your blog in a comment in order to bring them into a conversation they might otherwise be missing. 

== Description ==

This was built out of a need for a way to bring registered users into a conversation unfolding in comments on posts. I work with an art collective and we're all posting multiple posts on multiple topics and as we've tried to shift more and more of our conversations out of email and onto our website, there didn't seem to be an easy way to bring specific people into these comment conversations without using a more general comment notification plugin. Notify Tagged User attempts to change that.

This plugin would help out any multi author blogs, and in particular, teams or project blogs that focus on internal communication.

Basically the plugin works by using the familiar Twitter @user reply syntax in the following way:
-check all new comments for the @user syntax
-if tagged users are found, look through the database for registered users' nicknames matching the tagged user
-if a registered user's nickname is found, get the associated email address
-send an email to all users tagged asking them to join in on the conversation

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `tagUserNotification.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

= Can multiple users be tagged? =

Yes, the plugin works whether you tag one or multiple users.

= Do I need to know the user's nickname? =

Yes, you need to know the user's nickname as defined in the User settings in Wordpress, as this is the search term used to find an associated email address for notification.


== Screenshots ==

== Changelog ==

= 1.0 =
