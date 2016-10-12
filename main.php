<?php
/*
Plugin Name: simple-optimization
Plugin URI: https://github.com/khaledMohammed000/wp-plugin-simple-optimization
Description: wp optimizer plugin to boost loading time and also with SEO twerks
Version: 1.0
Author: Mohammed Khaled
Author URI: http://khaledmohammed.me
License: MIT
.
Any other notes about the plugin go here
.
*/

//cleaning wp_head action
//removed really simple discovery function below
remove_action('wp_head','rsd_link');

//removed windows live writer link
remove_action('wp_head','wlwmanifest_link');

//remove wp - version number
remove_action('wp_head','wp_generator');


// removing curly quotes functionality from filters
remove_filter('the_content','wptexturize');
remove_filter('comment_text','wptexturize');

//allow HTML in user profiles
remove_filter('pre_user_description','wp_filter_kses');