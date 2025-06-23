<?php
namespace SCM;

defined('ABSPATH') || exit;

class Core {
    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->includes();
        add_action('init', [$this, 'load_textdomain']);
        add_filter('the_content', [$this, 'maybe_restrict_content']);
        add_shortcode('scm_restrict', [$this, 'handle_restrict_shortcode']);
    }
    public function handle_restrict_shortcode($atts, $content = '') {
        // Default: logged-in users only
        $atts = shortcode_atts([
            'roles' => '', // comma-separated
        ], $atts, 'scm_restrict');
    
        // Must be logged in
        if (!is_user_logged_in()) {
            return get_option('scm_restricted_login_msg', '<div class="scm-restricted-msg"><em>This content is for logged-in users only.</em></div>');
        }
    
        // Role-based check
        if (!empty($atts['roles'])) {
            $roles = array_map('trim', explode(',', $atts['roles']));
            $user = wp_get_current_user();
            $has_role = false;
    
            foreach ($roles as $role) {
                if (in_array($role, $user->roles)) {
                    $has_role = true;
                    break;
                }
            }
    
            if (!$has_role) {
                return get_option('scm_restricted_role_msg', '<div class="scm-restricted-msg"><em>You do not have permission to view this content.</em></div>');
            }
        }
    
        // All good – return content
        return do_shortcode($content);
    }
    

    private function includes() {
        require_once plugin_dir_path(__FILE__) . 'class-scm-admin.php';
        Admin::get_instance();
    }

    public function load_textdomain() {
        load_plugin_textdomain('smart-content-manager', false, dirname(plugin_basename(__FILE__)) . '/../languages');
    }

    public function maybe_restrict_content($content) {
        if (!is_singular() || is_admin()) return $content;

        global $post;

        $only_logged_in = get_post_meta($post->ID, '_scm_logged_in', true);
        $allowed_roles = get_post_meta($post->ID, '_scm_roles', true);

        // Logged-in check
        if ($only_logged_in && !is_user_logged_in()) {
            return get_option('scm_restricted_login_msg', '<div class="scm-restricted-msg"><em>This content is for logged-in users only.</em></div>');
        }

        // Role check
        if (!empty($allowed_roles) && is_array($allowed_roles)) {
            $user = wp_get_current_user();
            $has_access = false;

            foreach ($allowed_roles as $role) {
                if (in_array($role, $user->roles)) {
                    $has_access = true;
                    break;
                }
            }

            if (!$has_access) {
                return get_option('scm_restricted_role_msg', '<div class="scm-restricted-msg"><em>You do not have permission to view this content.</em></div>');
            }
        }

        return $content;
    }
}
