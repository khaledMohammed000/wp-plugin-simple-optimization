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

/**
 * The main diff between filters and actions is
 * actions dont return anything and dont take arguments
 * whereas filters do
 */


// adding SEO functionality to plugin
//adds keywords from tags of posts
function tag_to_keywords(){
    global $post;
    if(is_single() || is_page()){
        $tags = wp_get_post_tags($post->ID);
        foreach($tags as $tag){
            $tag_array[]=$tag->name;
        }
        $tag_string = implode(', ',$tag_array);
        if($tag_string !== ''){
            echo "<meta name='keywords' content='".$tag_array."'/>\r\n";
        }
    }
}

add_action('wp_head','tag_to_keywords');

//now adding Ddescription to header for same SEO purpose

function excerpt_to_description(){
    global $post;
    if(is_single() || is_post()){
        $all_post_content = wp_get_single_post($post->ID);
        $excerpt = substr($all_post_content->post_content,0,100).' [...]';
        echo "<meta name='description' content='".$excerpt."'/>\r\n";
    }else{
        echo "<meta name='description' content='".get_bloginfo('description')."'/>\r\n";
    }
}

add_action('wp_head','excerpt_to_description');

//adding code to optimize database queries
function optimize_database(){
    global $wpdb;
    $all_tables=$wpdb->get_results('SHOW TABLES',ARRAY_A);
    foreach ($all_tables as $tables){
        $table = array_values($tables);
        $wpdb->query("OPTIMIZE TABLE".$table[0]);
    }
}

function optimization_cron_on(){
	wp_schedule_event(time(),'daily','optimize_database');
}

function optimization_cron_off(){
	wp_clear_scheduled_hook('optimize_database');
}

register_activation_hook(__FILE__,'optimization_cron_on');
register_deactivation_hook(__FILE__,'optimization_cron_off');