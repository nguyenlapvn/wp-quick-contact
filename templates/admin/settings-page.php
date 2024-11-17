<?php defined('ABSPATH') || exit; ?>

<div class="wrap">
    <h1><?php echo esc_html__('Quick Contact Settings', 'wp-quick-contact'); ?></h1>
    
    <div class="wpqc-admin-container">
        <form id="wpqc-settings-form" method="post">
            <div class="wpqc-buttons-container">
                <div id="wpqc-buttons-list">
                    <?php 
                    if (!empty($buttons)) {
                        foreach ($buttons as $button) {
                            require WPQC_DIR . 'templates/admin/button-item.php';
                        }
                    }
                    ?>
                </div>
                
                <button type="button" id="wpqc-add-button" class="button button-secondary">
                    <?php _e('Add New Button', 'wp-quick-contact'); ?>
                </button>
            </div>

            <div class="wpqc-submit-container">
                <button type="submit" class="button button-primary">
                    <?php _e('Save Changes', 'wp-quick-contact'); ?>
                </button>
                <span class="spinner"></span>
            </div>
        </form>
    </div>
</div>