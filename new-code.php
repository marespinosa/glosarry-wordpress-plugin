<?php 
function logo_slider_custom() {

	$labels = array(
		'name'               => __( 'Logo Slider' ),
		'singular_name'      => __( 'Logo Slide' ),
		'add_new'            => __( 'Add New' ),
		'add_new_item'       => __( 'Add New Item' ),
		'edit_item'          => __( 'Edit item' ),
		'new_item'           => __( 'Add New Logo' ),
		'view_item'          => __( 'View Logo' ),
		'search_items'       => __( 'Search Logo' ),
		'not_found'          => __( 'No Logo found' ),
		'not_found_in_trash' => __( 'No Logo' )
	);

	$supports = array(
		'title',
		'editor',
		'thumbnail',
		'custom-fields',
	);
	

	$args = array(
		'labels'               => $labels,
		'supports'             => $supports,
		'taxonomies'   		   => array('category'),
		'public'               => true,
		'capability_type'      => 'post',
		'rewrite'              => array( 'slug' => 'logo' ),
		'has_archive'          => true,
		'menu_position'        => 30,
		'menu_icon'            => 'dashicons-format-aside',
	);

	register_post_type( 'logo_slide_carousel', $args );

}
add_action( 'init', 'logo_slider_custom' );

function diwp_create_shortcode_movies_post_type(){
 
    $args = array(
                    'post_type'      => 'movies',
                    'posts_per_page' => '10',
                    'publish_status' => 'published',
                 );
 
    $query = new WP_Query($args);
 
    if($query->have_posts()) :
 
        while($query->have_posts()) :
 
            $query->the_post() ;
                     
        $result .= '<div class="movie-item">';
        $result .= '<div class="movie-poster">' . get_the_post_thumbnail() . '</div>';
        $result .= '<div class="movie-name">' . get_the_title() . '</div>';
        $result .= '<div class="movie-desc">' . get_the_content() . '</div>'; 
        $result .= '</div>';
 
        endwhile;
 
        wp_reset_postdata();
 
    endif;    
 
    return $result;            
}
 
add_shortcode( 'movies-list', 'diwp_create_shortcode_movies_post_type' ); 
?>