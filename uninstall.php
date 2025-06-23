<?php
// If uninstall not called from WordPress, exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete post meta
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key IN ('_scm_logged_in', '_scm_roles')");

// Delete options
delete_option('scm_restricted_login_msg');
delete_option('scm_restricted_role_msg');
