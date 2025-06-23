<?php
namespace SCM;

defined('ABSPATH') || exit;

class Admin {
    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [$this, 'add_settings_menu']);
        add_action('add_meta_boxes', [$this, 'add_restriction_meta_box']);
        add_action('save_post', [$this, 'save_restriction_meta']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_restriction_meta_box() {
        add_meta_box(
            'scm_restrictions',
            __('Content Access Restrictions', 'smart-content-manager'),
            [$this, 'render_meta_box'],
            ['post', 'page'],
            'side',
            'default'
        );
    }

    public function render_meta_box($post) {
        wp_nonce_field('scm_save_meta', 'scm_meta_nonce');

        $allowed_roles = get_post_meta($post->ID, '_scm_roles', true);
        $only_logged_in = get_post_meta($post->ID, '_scm_logged_in', true);
        ?>
        <p><label><input type="checkbox" name="scm_logged_in" value="1" <?php checked($only_logged_in, '1'); ?> /> <?php _e('Only Logged-in Users', 'smart-content-manager'); ?></label></p>

        <p><strong><?php _e('Allowed Roles', 'smart-content-manager'); ?></strong></p>
        <?php
        global $wp_roles;
        foreach ($wp_roles->roles as $role_key => $role) {
            ?>
            <label><input type="checkbox" name="scm_roles[]" value="<?php echo esc_attr($role_key); ?>" <?php if (is_array($allowed_roles) && in_array($role_key, $allowed_roles)) echo 'checked'; ?> /> <?php echo esc_html($role['name']); ?></label><br/>
            <?php
        }
    }

    public function save_restriction_meta($post_id) {
        if (!isset($_POST['scm_meta_nonce']) || !wp_verify_nonce($_POST['scm_meta_nonce'], 'scm_save_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        update_post_meta($post_id, '_scm_logged_in', isset($_POST['scm_logged_in']) ? '1' : '0');
        update_post_meta($post_id, '_scm_roles', isset($_POST['scm_roles']) ? array_map('sanitize_text_field', $_POST['scm_roles']) : []);
    }
    public function add_settings_menu() {
        add_options_page(
            __('Smart Content Manager Settings', 'smart-content-manager'),
            __('SCM Settings', 'smart-content-manager'),
            'manage_options',
            'scm-settings',
            [$this, 'render_settings_page']
        );
    }
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Smart Content Manager Settings', 'smart-content-manager'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('scm_settings_group');
                do_settings_sections('scm-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    public function register_settings() {
        register_setting('scm_settings_group', 'scm_restricted_login_msg', [
            'sanitize_callback' => 'sanitize_textarea_field',
            'default' => '<div class="scm-restricted-msg"><em>This content is for logged-in users only.</em></div>',
        ]);
    
        register_setting('scm_settings_group', 'scm_restricted_role_msg', [
            'sanitize_callback' => 'sanitize_textarea_field',
            'default' => '<div class="scm-restricted-msg"><em>You do not have permission to view this content.</em></div>',
        ]);
    
        add_settings_section('scm_section', '', null, 'scm-settings');
    
        add_settings_field(
            'scm_restricted_login_msg',
            __('Login Restriction Message', 'smart-content-manager'),
            function() {
                $val = get_option('scm_restricted_login_msg', '');
                echo '<textarea name="scm_restricted_login_msg" rows="3" class="large-text">' . esc_textarea($val) . '</textarea>';
            },
            'scm-settings',
            'scm_section'
        );
    
        add_settings_field(
            'scm_restricted_role_msg',
            __('Role Restriction Message', 'smart-content-manager'),
            function() {
                $val = get_option('scm_restricted_role_msg', '');
                echo '<textarea name="scm_restricted_role_msg" rows="3" class="large-text">' . esc_textarea($val) . '</textarea>';
            },
            'scm-settings',
            'scm_section'
        );
    }
    
    
    
}