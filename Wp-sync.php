<?php
/**
 * Plugin Name:       Synchronizer
 * Plugin URI:        https://yhello.co
 * Description:       Sync contents from child sites to a central one. Also does few more things.
 * Version:           1.0
 * Author:            Yhello
 * Author URI:        https://yhello.co
 * Text Domain:       synchronizer
 * Domain Path:       /languages
 * Requires at least: 5.0
 * Tested up to:      5.2.2
 *
 * @link              https://yhello.co
 * @since             1.0
 * @package           synchronizer
 */


define( 'WP_SYNC_VERSION',               '0.0.1' );
define( 'WP_SYNC_PRIVATE_KEY',           false );
define( 'WP_SYNC_FILE',                  __FILE__ );
define( 'WP_SYNC_PATH',                  realpath( plugin_dir_path( WP_SYNC_FILE ) ) . '/' );
define( 'WP_SYNC_INC_PATH',              realpath( WP_SYNC_PATH . 'inc/' ) . '/' );

require WP_ROCKET_INC_PATH . 'functions.php';
