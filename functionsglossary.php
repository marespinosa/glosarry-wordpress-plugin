<?php

/**
 
 * @package Glossary Plugin
 
 */
 
/*
 
Plugin Name: Glossary Plugin
 
Plugin URI: http://maricon-espinosa.online/
 
Description: Private plugin
 
Version: 1.0.1
 
Author: Maricon Espinosa
 
Author URI: http://maricon-espinosa.online/
 
License: GPLv2 or later
 
Text Domain: glossary

 */

function products_post_custom() {

	$labels = array(
		'name'               => __( 'Products' ),
		'singular_name'      => __( 'Product' ),
		'add_new'            => __( 'Add New' ),
		'add_new_item'       => __( 'Add New Item' ),
		'edit_item'          => __( 'Edit item' ),
		'new_item'           => __( 'Add New Product' ),
		'view_item'          => __( 'View Product' ),
		'search_items'       => __( 'Search Product' ),
		'not_found'          => __( 'No Product found' ),
		'not_found_in_trash' => __( 'No Product' )
	);

	$supports = array(
		'title',
		'editor',
		'thumbnail',
		'excerpt',
		'custom-fields',
	);
	

	$args = array(
		'labels'               => $labels,
		'supports'             => $supports,
		'taxonomies'   		   => array('category'),
		'public'               => true,
		'capability_type'      => 'post',
		'rewrite'              => array( 'slug' => 'products' ),
		'has_archive'          => true,
		'menu_position'        => 30,
		'menu_icon'            => 'dashicons-products',
	);

	register_post_type( 'products-list', $args );

}
add_action( 'init', 'products_post_custom' );






function glossary_post_custom() {

	$labels = array(
		'name'               => __( 'Glossary' ),
		'singular_name'      => __( 'glossary' ),
		'add_new'            => __( 'Add New' ),
		'add_new_item'       => __( 'Add New Item' ),
		'edit_item'          => __( 'Edit item' ),
		'new_item'           => __( 'Add New Glossary' ),
		'view_item'          => __( 'View Glossary' ),
		'search_items'       => __( 'Search Glossary' ),
		'not_found'          => __( 'No Glossary found' ),
		'not_found_in_trash' => __( 'No Glossary' )
	);

	$supports = array(
		'title',
		'editor',
		'thumbnail',
		'excerpt',
		'custom-fields',
	);
	

	$args = array(
		'labels'               => $labels,
		'supports'             => $supports,
		'taxonomies'   		   => array('category-list'),
		'public'               => true,
		'capability_type'      => 'post',
		'rewrite'              => array( 'slug' => 'glossary' ),
		'has_archive'          => true,
		'menu_position'        => 30,
		'menu_icon'            => 'dashicons-format-aside',
	);

	register_post_type( 'glossary-list', $args );

}
add_action( 'init', 'glossary_post_custom' );



function faqs_post_custom() {

	$labels = array(
		'name'               => __( 'FAQs' ),
		'singular_name'      => __( 'FAQ' ),
		'add_new'            => __( 'Add New' ),
		'add_new_item'       => __( 'Add New Question' ),
		'edit_item'          => __( 'Edit Question' ),
		'new_item'           => __( 'Add New Item' ),
		'view_item'          => __( 'View Item' ),
		'search_items'       => __( 'Search FAQ' ),
		'not_found'          => __( 'No FAQ found' ),
		'not_found_in_trash' => __( 'No FAQ found in trash' )
	);

	$supports = array(
		'title',
		'editor',
		'thumbnail',
		'excerpt',
		'custom-fields',
	);
	
	

	$args = array(
		'labels'               => $labels,
		'supports'             => $supports,
		'public'               => true,
		'capability_type'      => 'post',
		'rewrite'              => array( 'slug' => 'faqs' ),
		'has_archive'          => true,
		'menu_position'        => 30,
		'menu_icon'            => 'dashicons-networking',
		'register_meta_box_cb' => 'start_end_metabox',
	);

	register_post_type( 'faqs', $args );

}
add_action( 'init', 'faqs_post_custom' );



function start_end_metabox() {
	
	add_meta_box(
		'start_date',
		'Date Published',
		'start_date',
		'faqs',
		'side',
		'default'
	);
	
	add_meta_box(
		'end_date',
		'Last Updated',
		'end_date',
		'faqs',
		'side',
		'default'
	);
}



function end_date() {
	global $post;
	wp_nonce_field( basename( __FILE__ ), 'faq_fields' );
	$enddate = get_post_meta( $post->ID, 'enddate', true );

	echo '<input type="date" name="enddate" value="' . esc_textarea( $enddate )  . '" class="widefat" >';

}
function start_date() {
	global $post;
	wp_nonce_field( basename( __FILE__ ), 'start_date_event' );
	$datestart = get_post_meta( $post->ID, 'datestart', true );

	echo '<input type="date" name="datestart" value="' . esc_textarea( $datestart )  . '" class="widefat" >'; }
	
	

function save_datas( $post_id, $post ) {

	if ( ! current_user_can( 'edit_post', $post_id ) ) { return $post_id; }

	if ( ! isset( $_POST['enddate'] ) || ! wp_verify_nonce( $_POST['faq_fields'], basename(__FILE__) ) ) {
		return $post_id;
		
	} else if ( ! isset( $_POST['datestart'] ) || ! wp_verify_nonce( $_POST['start_date_event'], basename(__FILE__) ) ) {
		return $post_id;
	}

	$events_meta['enddate'] = esc_textarea( $_POST['enddate'] );
	$events_meta['datestart'] = esc_textarea( $_POST['datestart'] );
	foreach ( $events_meta as $key => $value ) :
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			update_post_meta( $post_id, $key, $value );
		} else {
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			delete_post_meta( $post_id, $key );
		}

	endforeach;

}
add_action( 'save_post', 'save_datas', 1, 2 );




function application_post_custom() {

	$labels = array(
		'name'               => __( 'Application' ),
		'singular_name'      => __( 'Application' ),
		'add_new'            => __( 'Add New' ),
		'add_new_item'       => __( 'Add New Item' ),
		'edit_item'          => __( 'Edit item' ),
		'new_item'           => __( 'Add New Application' ),
		'view_item'          => __( 'View Application' ),
		'search_items'       => __( 'Search Application' ),
		'not_found'          => __( 'No Application found' ),
		'not_found_in_trash' => __( 'No Application' )
	);

	$supports = array(
		'title',
		'editor',
		'thumbnail',
		'excerpt',
		'custom-fields',
	);
	

	$args = array(
		'labels'               => $labels,
		'supports'             => $supports,
		'taxonomies'   		   => array('notrecommended-list', 'safety-list', 'malicious-list'),
		'public'               => true,
		'capability_type'      => 'post',
		'rewrite'              => array( 'slug' => 'application' ),
		'has_archive'          => true,
		'menu_position'        => 30,
		'menu_icon'            => 'dashicons-forms',
	);

	register_post_type( 'application-list', $args );

}
add_action( 'init', 'application_post_custom' );




add_action( 'init', 'safety_hierarchical_taxonomy', 0 );
 
function safety_hierarchical_taxonomy() {
 
 
  $labels = array(
    'name' => ( 'Safe' ),
    'singular_name' => ( 'Safe' ),
    'search_items' =>  __( 'Search Safe' ),
    'all_items' => __( 'All Safe' ),
    'parent_item' => __( 'Parent Safe' ),
    'parent_item_colon' => __( 'Parent Safe:' ),
    'edit_item' => __( 'Edit Safe' ), 
    'update_item' => __( 'Update Safe' ),
    'add_new_item' => __( 'Add New Safe' ),
    'new_item_name' => __( 'New Safe Name' ),
    'menu_name' => __( 'Safe' ),
  );    
 
  register_taxonomy('safety-list', array('application-list'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'safety-list' ),
  ));
 
}



add_action( 'init', 'platform_hierarchical_taxonomy', 0 );
 
function platform_hierarchical_taxonomy() {
 
 
  $labels = array(
    'name' => ( 'Not Recommended' ),
    'singular_name' => ( 'Not Recommended' ),
    'search_items' =>  __( 'Search Not Recommended' ),
    'all_items' => __( 'All Not Recommended' ),
    'parent_item' => __( 'Item for Not Recommended' ),
    'parent_item_colon' => __( 'Not Recommended:' ),
    'edit_item' => __( 'Edit' ), 
    'update_item' => __( 'Update' ),
    'add_new_item' => __( 'Add New' ),
    'new_item_name' => __( 'New' ),
    'menu_name' => __( 'Not Recommended' ),
  );    
 

 
  register_taxonomy('notrecommended-list', array('application-list'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'notrecommended-list' ),
  ));
 
}




add_action( 'init', 'malicious_hierarchical_taxonomy', 0 );
 
function malicious_hierarchical_taxonomy() {
 
 
  $labels = array(
    'name' => ( 'Malicious' ),
    'singular_name' => ( 'Malicious' ),
    'search_items' =>  __( 'Search Malicious' ),
    'all_items' => __( 'All Malicious' ),
    'parent_item' => __( 'Item for Malicious' ),
    'parent_item_colon' => __( 'Malicious:' ),
    'edit_item' => __( 'Edit' ), 
    'update_item' => __( 'Update' ),
    'add_new_item' => __( 'Add New' ),
    'new_item_name' => __( 'New' ),
    'menu_name' => __( 'Malicious' ),
  );    
 

 
  register_taxonomy('malicious-list',array('application-list'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'malicious-list' ),
  ));
 
}


add_action( 'init', 'categoryglo_hierarchical_taxonomy', 0 );
 
function categoryglo_hierarchical_taxonomy() {
 
 
  $labels = array(
    'name' => ( 'Categories' ),
    'singular_name' => ( 'Category' ),
    'search_items' =>  __( 'Search Categories' ),
    'all_items' => __( 'All Categories' ),
    'parent_item' => __( 'Item for Categories' ),
    'parent_item_colon' => __( 'Categories:' ),
    'edit_item' => __( 'Edit' ), 
    'update_item' => __( 'Update' ),
    'add_new_item' => __( 'Add New' ),
    'new_item_name' => __( 'New' ),
    'menu_name' => __( 'Categories' ),
  );    
 

 
  register_taxonomy('category-list',array('application-list'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'category-list' ),
  ));
 
}



?>