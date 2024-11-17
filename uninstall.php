<?php
// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete all plugin options
delete_option('wpqc_buttons');

// Delete any other options and custom tables if needed
// Example: delete_option('wpqc_settings');

// Clear any cached data
wp_cache_flush();