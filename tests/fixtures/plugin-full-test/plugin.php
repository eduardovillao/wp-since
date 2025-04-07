<?php

register_setting('my_option_group', 'my_option');
new WP_Query();

MyPlugin::boot();

do_action('my_custom_hook');
apply_filters('my_filter', 'value');