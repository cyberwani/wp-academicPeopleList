<?php
/*
Plugin Name: WP Academic People List
Plugin URI: http://salehalsaffar.com/blog/?page_id=834 
Description: Provides the ability to profile users academically and create categories of academic people. You also show Academic people list using shortcode. This is useful for school alumni and research group website.
Version: 0.1
Author: Saleh N. Alsaffar
Author URI: http://salehalsaffar.com/
License: GPL2
*/
/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : saleh@salehalsaffar.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


global $wpapl_prefix;
$wpapl_prefix = "wpapl";
global $wpapl_plugin_version;
$wpapl_plugin_version = "0.1";
global $wpapl_people_table_name;
global $wpdb;
$wpapl_people_table_name = $wpdb->prefix . $wpapl_prefix . "_people";
global $wpapl_category_table_name;
$wpapl_category_table_name = $wpdb->prefix . $wpapl_prefix . "_category";

// Include the CSS file to the plugin
function admin_register_head() {
	$siteurl = get_option('siteurl');
	$url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/style.css';
	echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}
add_action('admin_head', 'admin_register_head');

// Includes functions file
require_once('functions.php');
// Load CSS
wp_enqueue_style( 'wpapl-style', get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/style.css', false, false, 'all' );

// Function that is called when activating the plugin
function wpapl_install() {
	global $wpdb, $wpapl_prefix, $wpapl_people_table_name, $wpapl_category_table_name;
	
	// Check if the table already exists
	if( $wpdb->get_var( "SHOW TABLES LIKE '$wpapl_people_table_name' ") != $wpapl_people_table_name ){ 
		// SQL statement for people table
		$sql = "CREATE TABLE " . $wpapl_people_table_name . " (  
			userID bigint NOT NULL AUTO_INCREMENT,
			middle_initial tinytext,
			url tinytext,
			academic_email tinytext,
			current_job mediumtext,
			bio text,
			PhD_field tinytext,
			PhD_institution tinytext,
			PhD_year tinytext,
			MS_field tinytext,
			MS_institution tinytext,
			MS_year tinytext,
			BS_field tinytext,
			BS_institution tinytext,
			BS_year tinytext,
			address text,
			phone_number tinytext,
			categoryID int,
			UNIQUE KEY userID (userID)
			);";
		
		// Execute query to create table
		$wpdb->query( $wpdb->escape( $sql ) );
		
	}
	if( $wpdb->get_var( "SHOW TABLES LIKE '$wpapl_category_table_name' ") != $wpapl_category_table_name ){ 
		
		// SQL statement for people_type table
		$sql2 = "CREATE TABLE " . $wpapl_category_table_name . " (  
			categoryID int NOT NULL AUTO_INCREMENT,
			category_name tinytext,
			UNIQUE KEY categoryID (categoryID)
			);";
		
		// Execute query to create table
		$wpdb->query( $wpdb->escape( $sql2 ) );
	}
	
	// Register plugin version
	global $wpapl_plugin_version;
	add_option( "wpapl_db_version", $wpapl_plugin_version );
}


// Register the function that will be called when the plugin is activated
register_activation_hook(__FILE__,'wpapl_install');


// Includes the administrative menu file
require_once('admin-panel.php');


// Add shortcode
require_once('shortcode.php');
add_shortcode("academic-people-list", 'wpapl_shortcode');





?>