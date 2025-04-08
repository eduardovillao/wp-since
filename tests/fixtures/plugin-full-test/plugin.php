<?php

add_option('foo', 'bar');

$query = new WP_Query();

WP_Filesystem::get_contents('/some/path');

$user = new WP_User();
$user->add_cap('edit_posts');

do_action('my_custom_hook', 'param');
apply_filters('my_filter_hook', 'value');