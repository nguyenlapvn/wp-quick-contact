<?php
class WPQC_Loader {
    protected $admin;
    protected $public;
    
    public function __construct() {
        $this->load_dependencies();
        $this->set_locale();
        $this->define_hooks();
    }
    
    private function load_dependencies() {
        $this->admin = new WPQC_Admin();
        $this->public = new WPQC_Public();
    }
    
    private function set_locale() {
        add_action('plugins_loaded', function() {
            load_plugin_textdomain(
                'wp-quick-contact',
                false,
                dirname(WPQC_BASENAME) . '/languages/'
            );
        });
    }
    
    private function define_hooks() {
        // Admin hooks
        add_action('admin_enqueue_scripts', [$this->admin, 'enqueue_assets']);
        add_action('admin_menu', [$this->admin, 'add_menu_page']);
        
        // Public hooks
        add_action('wp_enqueue_scripts', [$this->public, 'enqueue_assets']);
        add_action('wp_footer', [$this->public, 'render_buttons']);
    }
    
    public function run() {
        do_action('wpqc_loaded');
    }
}