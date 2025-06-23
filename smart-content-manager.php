<?php
/**
 * Plugin Name: Smart Content Manager
 * Description: Restrict post or page content by user role, login status, or user meta.
 * Version: 1.0.0
 * Author: Muzammal Rasool
 * Text Domain: smart-content-manager
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') || exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-scm-core.php';

function scm_init_plugin() {
    \SCM\Core::get_instance();
}
add_action('plugins_loaded', 'scm_init_plugin');
