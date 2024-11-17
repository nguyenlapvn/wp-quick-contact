<?php
class WPQC_Admin {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_wpqc_save_buttons', [$this, 'ajax_save_buttons']);
        add_action('wp_ajax_wpqc_get_button_template', [$this, 'ajax_get_button_template']);
    }

    public function get_button_types() {
        return [
            'messenger' => __('Messenger', 'wp-quick-contact'),
            'zalo' => __('Zalo', 'wp-quick-contact'),
            'telegram' => __('Telegram', 'wp-quick-contact'),
            'phone' => __('Phone', 'wp-quick-contact'),
            'whatsapp' => __('WhatsApp', 'wp-quick-contact'),
            'viber' => __('Viber', 'wp-quick-contact'),
            'line' => __('Line', 'wp-quick-contact'),
            'discord' => __('Discord', 'wp-quick-contact'),
            'custom' => __('Custom', 'wp-quick-contact'),
        ];
    }

    public function add_menu_page() {
        add_options_page(
            __('Quick Contact Settings', 'wp-quick-contact'),
            __('WP Quick Contact', 'wp-quick-contact'),
            'manage_options',
            'wpqc-settings',
            [$this, 'render_settings_page']
        );
    }

    public function enqueue_assets($hook) {
        if ('settings_page_wpqc-settings' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'wpqc-admin',
            WPQC_URL . 'assets/css/admin.css',
            [],
            WPQC_VERSION
        );
        
        wp_enqueue_script(
            'wpqc-admin',
            WPQC_URL . 'assets/js/admin.js',
            ['jquery'],
            WPQC_VERSION,
            true
        );

        wp_localize_script('wpqc-admin', 'wpqc_admin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wpqc_admin_nonce'),
            'i18n' => [
                'confirm_delete' => __('Are you sure you want to delete this button?', 'wp-quick-contact'),
                'confirm_reset' => __('Are you sure you want to reset all settings?', 'wp-quick-contact'),
                'save_success' => __('Settings saved successfully!', 'wp-quick-contact'),
                'save_error' => __('Error saving settings. Please try again.', 'wp-quick-contact'),
                'validation_error' => __('Please fill in all required fields.', 'wp-quick-contact'),
                'network_error' => __('Network error. Please try again.', 'wp-quick-contact')
            ]
        ]);
    }

    public function register_settings() {
        register_setting('wpqc_options', 'wpqc_buttons');
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $button_types = $this->get_button_types();
        $buttons = wpqc()->get_active_buttons();

        include WPQC_DIR . 'templates/admin/settings-page.php';
    }

    public function ajax_save_buttons() {
        check_ajax_referer('wpqc_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        $buttons = isset($_POST['buttons']) ? json_decode(wp_unslash($_POST['buttons']), true) : [];
        
        if (!is_array($buttons)) {
            wp_send_json_error('Invalid data format');
        }

        // Sanitize input
        $sanitized_buttons = [];
        foreach ($buttons as $button) {
            if (empty($button['id']) || empty($button['name']) || empty($button['type'])) {
                continue;
            }

            $sanitized_button = [
                'id' => sanitize_key($button['id']),
                'name' => sanitize_text_field($button['name']),
                'type' => sanitize_key($button['type']),
                'url' => esc_url_raw($button['url'])
            ];

            if ($button['type'] === 'custom' && !empty($button['custom_svg'])) {
                $allowed_html = [
                    'svg' => [
                        'xmlns' => true,
                        'viewbox' => true,
                        'width' => true,
                        'height' => true,
                        'fill' => true,
                        'class' => true,
                    ],
                    'path' => [
                        'd' => true,
                        'fill' => true,
                        'stroke' => true,
                        'stroke-width' => true,
                    ],
                    'circle' => [
                        'cx' => true,
                        'cy' => true,
                        'r' => true,
                        'fill' => true,
                    ],
                    'rect' => [
                        'x' => true,
                        'y' => true,
                        'width' => true,
                        'height' => true,
                        'fill' => true,
                    ]
                ];
                
                $sanitized_button['custom_svg'] = wp_kses($button['custom_svg'], $allowed_html);
            }

            $sanitized_buttons[] = $sanitized_button;
        }

        update_option('wpqc_buttons', $sanitized_buttons);
        wp_send_json_success();
    }

    public function ajax_get_button_template() {
        check_ajax_referer('wpqc_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die();
        }

        $button = json_decode(wp_unslash($_POST['button']), true);
        $button_types = $this->get_button_types();
        
        ob_start();
        include WPQC_DIR . 'templates/admin/button-item.php';
        $html = ob_get_clean();
        
        echo $html;
        wp_die();
    }
}