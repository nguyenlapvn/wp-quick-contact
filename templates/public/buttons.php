<?php
class WPQC_Public {
    private function get_svg_content($filename) {
        $file_path = WPQC_DIR . 'assets/images/' . $filename . '.svg';
        if (file_exists($file_path)) {
            return file_get_contents($file_path);
        }
        return '';
    }

    public function render_buttons() {
        $buttons = wpqc()->get_active_buttons();
        error_log('WPQC Buttons: ' . print_r($buttons, true));  // Thêm dòng này

        if (empty($buttons)) return;
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
                            <?php echo $this->get_svg_content($button['type']); ?>
                            <span class="wpqc-button-label"><?php echo esc_html($button['name']); ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}