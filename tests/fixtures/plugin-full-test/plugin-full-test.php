<?php

/**
 * Plugin Name: My plugin
 * Plugin URI: https://myddelivery.com/
 * Description: Simple test plugin.
 * Author: EduardoVillao.me
 * Author URI: https://eduardovillao.me/
 * Version: 1.0
 * Requires PHP: 7.4
 * Requires at least: 6.2
 * Text Domain: test-plugin
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 */

add_option('foo', 'bar');

$query = new WP_Query();

WP_Filesystem::get_contents('/some/path');

$user = new WP_User();
$user->add_cap('edit_posts');

do_action('my_custom_hook', 'param');
apply_filters('my_filter_hook', 'value');
