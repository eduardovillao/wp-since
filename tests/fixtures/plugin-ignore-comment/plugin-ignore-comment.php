<?php

add_option('should_be_ignored'); // @wp-since ignore

do_action('should_be_ignored_space');     // @wp-since ignore

do_action('need_detect'); // simple comment

do_action('my_custom_hook');

register_setting('mygroup', 'myoption');

function isBlockTheme()
{
    if (function_exists( 'wp_is_block_theme')) {
        return wp_is_block_theme(); // @wp-since ignore
    }

    return false;
}

function exampleWithoutIgnore()
{
    if (function_exists('wp_detected_function')) {
        return wp_detected_function();
    }

    return false;
}
