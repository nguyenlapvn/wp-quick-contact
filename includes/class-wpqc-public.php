<?php
class WPQC_Public {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_footer', [$this, 'render_buttons']);
    }

    public function enqueue_assets() {
        wp_enqueue_style(
            'wpqc-public',
            WPQC_URL . 'assets/css/public.css',
            [],
            WPQC_VERSION
        );

        wp_enqueue_script(
            'wpqc-public',
            WPQC_URL . 'assets/js/public.js',
            [],
            WPQC_VERSION,
            true
        );
    }

    private function get_svg_content($type, $current_button = null) {
        // Nếu là custom icon và có truyền data của button hiện tại
        if ($type === 'custom' && $current_button && !empty($current_button['custom_svg'])) {
            return wp_kses($current_button['custom_svg'], wpqc()->get_allowed_svg_tags());
        }

        // Nếu không phải custom, get từ file
        $svg_file = WPQC_DIR . 'assets/images/' . $type . '.svg';
        if (file_exists($svg_file)) {
            $svg_content = file_get_contents($svg_file);
            if (strpos($svg_content, 'class="') === false) {
                $svg_content = str_replace('<svg', '<svg class="wpqc-icon"', $svg_content);
            }
            return wp_kses($svg_content, wpqc()->get_allowed_svg_tags());
        }
        
        // Return default icon nếu không có cả hai
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="wpqc-icon">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-4h2v2h-2zm0-2h2V7h-2z"/>
        </svg>';
    }

    public function render_buttons() {
        $buttons = wpqc()->get_active_buttons();
        if (empty($buttons)) {
            return;
        }
        ?>
        <div class="wpqc-container">
            <div class="wpqc-toggle">
                <div class="toggle-open">
                    <?php echo $this->get_svg_content('toggle'); ?>
                </div>
                <div class="toggle-close">
                    <?php echo $this->get_svg_content('close'); ?>
                </div>
            </div>
            
            <div class="wpqc-buttons">
                <?php foreach ($buttons as $button): ?>
                    <?php if (!empty($button['url'])): ?>
                        <a href="<?php echo esc_url($button['url']); ?>" 
                           class="wpqc-button" 
                           data-type="<?php echo esc_attr($button['type']); ?>"
                           target="_blank" 
                           rel="noopener">
                            <?php 
                            // Truyền thêm dữ liệu của button hiện tại vào function
                            echo $this->get_svg_content($button['type'], $button); 
                            ?>
                            <span class="wpqc-button-label">
                                <?php echo esc_html($button['name']); ?>
                            </span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}