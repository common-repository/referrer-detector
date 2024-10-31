<?php
/*
Plugin Name: Referrer Detector
Plugin URI: http://www.phoenixheart.net/wp-plugins/referrer-detector
Description: Helps your blog detect where the user comes from and automatically displays the corresponding greetings.
Version: 4.2.1.0
Author: Phoenixheart
Author URI: http://www.phoenixheart.net

STARTING FROM VERSION 4, THIS FILE ONLY ACTS AS AN INITIAL POINT
*/

require_once(dirname(__FILE__) . '/rd.class.php');
$rd = new ReferrerDetector();

register_activation_hook(__FILE__, array($rd, 'install'));

add_action('admin_menu', array($rd, 'register_menu'));

add_action('init', array($rd, 'handle_request'), 5);

add_action('init', array($rd, 'handle_public_request'), 5);

add_action('wp_head', array($rd, 'head'));

add_filter('the_content', array($rd, 'prepare_post_data'), 5);

function referrer_detector()
{
    echo '<div class="rdetector_placeholder_special" style="display: none"></div>';
}