<?php
class WPQC_Core {
    /**
     * Plugin instance
     * @var WPQC_Core
     */
    private static $instance = null;

    /**
     * Get plugin instance
     * @return WPQC_Core
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Private constructor để đảm bảo singleton pattern
    }

    /**
     * Get button icon SVG content
     * @param string $type
     * @return string
     */
    public function get_button_icon($type) {
        $svg_file = WPQC_DIR . 'assets/images/' . $type . '.svg';
        if (file_exists($svg_file)) {
            $svg_content = file_get_contents($svg_file);
            return wp_kses($svg_content, $this->get_allowed_svg_tags());
        }
        return '';
    }

    /**
     * Get allowed SVG tags and attributes for wp_kses
     * @return array
     */
    public function get_allowed_svg_tags() {
        return array(
            'svg' => array(
                'xmlns' => true,
                'viewbox' => true,
                'width' => true,
                'height' => true,
                'fill' => true,
                'class' => true,
                'style' => true,
            ),
            'path' => array(
                'd' => true,
                'fill' => true,
                'stroke' => true,
                'stroke-width' => true,
                'stroke-linecap' => true,
                'stroke-linejoin' => true,
                'class' => true,
                'style' => true,
            ),
            'circle' => array(
                'cx' => true,
                'cy' => true,
                'r' => true,
                'fill' => true,
                'class' => true,
                'style' => true,
            ),
            'rect' => array(
                'x' => true,
                'y' => true,
                'width' => true,
                'height' => true,
                'fill' => true,
                'class' => true,
                'style' => true,
            ),
            'g' => array(
                'fill' => true,
                'class' => true,
                'style' => true,
            ),
        );
    }

    /**
     * Get active buttons
     * @return array
     */
    public function get_active_buttons() {
        $buttons = get_option('wpqc_buttons', []);
        return apply_filters('wpqc_active_buttons', $buttons);
    }

    /**
     * Get plugin option
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get_option($key, $default = '') {
        return get_option('wpqc_' . $key, $default);
    }

    /**
     * Update plugin option
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function update_option($key, $value) {
        return update_option('wpqc_' . $key, $value);
    }

    /**
     * Delete plugin option
     * @param string $key
     * @return bool
     */
    public function delete_option($key) {
        return delete_option('wpqc_' . $key);
    }
}