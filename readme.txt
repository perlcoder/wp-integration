=== Wp Sync ===
Contributors: Márcio Marim, Marco Arthur
Tags: sync, child, parent
Requires at least: 5.2
Tested up to: 5.2.2
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sync from child wp sites using a parent wp

== Description ==

This plugins use the wordpress cron scheduler to make REST requests to a list
of wordpress sites, called here as child. So the parent can track records and
function as an aggregator.


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.


== Changelog ==

= 0.1 =

	Work with two child and get new and updated posts.

== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above.  This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation."  Arbitrary sections will be shown below the built-in sections outlined above.

