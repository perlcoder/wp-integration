<?php
/**
 * Callback for plugin loading.
 *
 */
function wp_sync_init() {
	false;
}
add_action('plugins_loaded', 'wp_sync_init');

/**
 * Callback for activation of the plugin.
 *
 */
function wp_sync_activation() {
	false;
}
register_activation_hook( WP_SYNC_FILE, 'wp_sync_activation' );
