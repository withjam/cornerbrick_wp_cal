<?php
/*
Author: Matt Pileggi
Plugin Name: The Corner Brick Calendar
Author URI: http://withjam.com
Plugin URI: http://thecornerbrickfairfield.com
Description: Booking calednar
Version: 1.0
Text Domain: tcb
*/

global $tcb_cal_db_version;
$tcb_call_db_version = '1.0';

function tcb_cal_install() {
	global $wpdb;
	global $tcb_call_db_version;

	$table_name = $wpdb->prefix . 'tcb_cal_event';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		dt_start date DEFAULT '0000-00-00' NOT NULL,
		dt_end date DEFAULT '0000-00-00' NOT NULL,
		name text NOT NULL,
		email varchar(255),
		phone varchar(20),
		desc text NOT NULL,
		status char(3) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'tcb_call_db_version', $tcb_call_db_version );
}

function tcb_cal_install_data() {
	global $wpdb;
	
	$welcome_name = 'Mr. WordPress';
	$welcome_text = 'Congratulations, you just completed the installation!';
	
	$table_name = $wpdb->prefix . 'tcb_cal_event';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'name' => $welcome_name, 
			'text' => $welcome_text, 
		) 
	);
}

function tcb_cal_update_db_check() {
    global $tcb_cal_db_version;
    if ( get_site_option( 'tcb_cal_db_version' ) != $tcb_cal_db_version ) {
        tcb_cal_install();
    }
}
add_action( 'plugins_loaded', 'tcb_cal_update_db_check' );

register_activation_hook( __FILE__, 'tcb_cal_install' );
register_activation_hook( __FILE__, 'tcb_cal_install_data' );