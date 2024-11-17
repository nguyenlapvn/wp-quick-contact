<?php
/**
 * Plugin Name: WP Quick Contact
 * Description: Add floating contact buttons on the bottom right corner of the screen
 * Version: 1.0
 * Author: Nguyen Lap
 * Author URI: https://nguyenlap.net
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * Text Domain: wp-quick-contact
 * Domain Path: /languages
 * License: GPL v2 or later
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WPQC_VERSION', '1.0.0');
define('WPQC_DIR', plugin_dir_path(__FILE__));
define('WPQC_URL', plugin_dir_url(__FILE__));
define('WPQC_BASENAME', plugin_basename(__FILE__));

// Autoloader cho các class
spl_autoload_register(function ($class_name) {
    if (strpos($class_name, 'WPQC_') !== false) {
        $class_name = strtolower($class_name);
        $class_name = str_replace('_', '-', $class_name);
        $file = WPQC_DIR . 'includes/class-' . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// Load Core class trước
require_once WPQC_DIR . 'includes/class-wpqc-core.php';

// Global access function
function wpqc() {
    return WPQC_Core::instance();
}

// Initialize core
wpqc();

// Load other classes
require_once WPQC_DIR . 'includes/class-wpqc-loader.php';
require_once WPQC_DIR . 'includes/class-wpqc-admin.php';
require_once WPQC_DIR . 'includes/class-wpqc-public.php';

// Begins execution of the plugin
function run_wpqc() {
    $plugin = new WPQC_Loader();
    $plugin->run();
}

// Run the plugin
run_wpqc();

add_action('plugins_loaded', function() {
    load_plugin_textdomain('wp-quick-contact', false, dirname(plugin_basename(__FILE__)) . '/languages');
});