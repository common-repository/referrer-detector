<?php
if(!defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN'))
    exit();
    
// 
require_once(dirname(__FILE__) . '/config.php');

/*
$rdetector_config['options'] = array(
    'rdetector_add_to_every_post',
    'rdetector_add_to_every_page',
    'rdetector_message_position',
    'rdetector_close_icon',
    'rdetector_related_posts',
    'rdetector_powered',
);
*/

foreach ($rdetector_config as $rd_key)
{
    delete_option($rd_key);
}

delete_option('rd_db_version');

// delete the tables
global $wpdb;
$wpdb->query("DROP TABLE `$rdetector_config[entries_table_name]`");
$wpdb->query("DROP TABLE `$rdetector_config[stats_table_name]`");