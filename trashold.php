<?php 
/**
 * Plugin Name: Trash Post/Media
 * Description: Trash Post And Old Media Libraries 
 * Version: 1.0.0
 * Author: Achyut Krishna Deka
 * Email: linkinakd72@gmail.com
 * License: GPL2
 */


add_action( 'admin_menu', 'my_trash_plugin_menu' );

function my_trash_plugin_menu() {
	add_menu_page( 'Plugin Options', 'Trash Post/Media Plugin', 'administrator', 'trash-post-options', 'trash_post_options' );	
	//add_submenu_page( 'plugin-all-options','Yelp Credentials','Yelp Credentials','administrator','yelp-credentials','yelp_credentials'); 
}

function trash_post_options (){
    
  include("postrash.php");  
    
}

add_filter( 'cron_schedules', 'bl_trash_post_intervals' );

	function bl_trash_post_intervals( $schedules ) {

	   $schedules['5seconds'] = array( 
	      'interval' => 1000, // Intervals are listed in seconds
	      'display' => __('Every 5 Seconds') // Easy to read display name
	   );
	   return $schedules; // Do not forget to give back the list of schedules!
	}

	add_action( 'bl_cron_hook_trash', 'trash_post' );

	if( !wp_next_scheduled( 'bl_cron_hook_trash' ) ) {
	   wp_schedule_event( time(), '5seconds', 'bl_cron_hook_trash' );
	}



	function trash_post($day) {

	 if(empty($day)){
	 	$day=7;
	 }	

	  $date = strtotime("-{$day} day"); $date = date('Y-m-d', $date);

	  $out = $GLOBALS['wpdb']->get_results(
	    "SELECT ID FROM " . $GLOBALS['wpdb']->posts .
	     " WHERE post_type = 'post' AND post_status = 'publish'  AND  post_date <=  '{$date}' "
	  ); 
	  foreach($out as $row){
	  	$post_id = $row->ID;

	  	$attachments = get_posts( array(
		    'post_type'      => 'attachment',
		    'nopaging'       => TRUE,
		    'post_parent'    => $post_id
	  	) );

	  	foreach ( $attachments as $attachment ) {
		   wp_delete_attachment( $attachment->ID );
		 }

		 wp_delete_post( $post_id , true );
	  }

	}


