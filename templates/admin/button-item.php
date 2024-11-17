<?php defined('ABSPATH') || exit; ?>

<div class="wpqc-button-item" data-id="<?php echo esc_attr($button['id']); ?>">
    <div class="wpqc-button-header">
        <span class="wpqc-button-move dashicons dashicons-move"></span>
        <span class="wpqc-button-title"><?php echo esc_html($button['name']); ?></span>
        <span class="wpqc-button-delete dashicons dashicons-trash"></span>
    </div>
    <div class="wpqc-button-content">
        <div class="wpqc-field-row">
            <label><?php _e('Button Name:', 'wp-quick-contact'); ?></label>
            <input type="text" class="wpqc-button-name" 
                   value="<?php echo esc_attr($button['name']); ?>" 
                   placeholder="<?php _e('Enter button name', 'wp-quick-contact'); ?>">
        </div>
        
        <div class="wpqc-field-row">
            <label><?php _e('Button Type:', 'wp-quick-contact'); ?></label>
            <select class="wpqc-button-type">
                <?php foreach ($button_types as $type => $label): ?>
                    <option value="<?php echo esc_attr($type); ?>" 
                            <?php selected($button['type'], $type); ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="wpqc-field-row">
            <label><?php _e('URL:', 'wp-quick-contact'); ?></label>
            <input type="url" class="wpqc-button-url" 
                   value="<?php echo esc_url($button['url']); ?>" 
                   placeholder="<?php _e('Enter full URL', 'wp-quick-contact'); ?>">
        </div>

        <div class="wpqc-field-row wpqc-custom-svg-field" 
             style="<?php echo ($button['type'] === 'custom') ? 'display:block;' : 'display:none;'; ?>">
            <label><?php _e('Custom SVG Icon:', 'wp-quick-contact'); ?></label>
            <textarea class="wpqc-button-custom-svg" rows="6" 
                      placeholder="<?php _e('Paste your SVG code here', 'wp-quick-contact'); ?>"><?php 
                echo esc_textarea($button['custom_svg'] ?? ''); 
            ?></textarea>
            <p class="description">
                <?php _e('Paste your SVG icon code. Make sure it\'s a valid SVG with viewBox="0 0 24 24".', 'wp-quick-contact'); ?>
            </p>
        </div>

        <input type="hidden" class="wpqc-button-id" value="<?php echo esc_attr($button['id']); ?>">
    </div>
</div>