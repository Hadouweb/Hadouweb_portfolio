<?php

function post_portfolio_type(){
    $labels = 
    array(
        'name' => __( 'Portfolio'), 		
        'singular_name' => __('Portfolio'),
        'rewrite' => 
                array(
                        'slug' => __( 'portfolio' ) 
                ),			
        'add_new' => _x('Add Item', 'portfolio'), 		
        'edit_item' => __('Edit Portfolio Item'),		
        'new_item' => __('New Portfolio Item'), 		
        'view_item' => __('View Portfolio'),		
        'search_items' => __('Search Portfolio'), 		
        'not_found' =>  __('No Portfolio Items Found'),		
        'not_found_in_trash' => __('No Portfolio Items Found In Trash'),		 
        'parent_item_colon' => '' 
    );
	
    $args = 
    array(
        'labels' => $labels, 
        'public' => true, 
        'publicly_queryable' => true, 
        'show_ui' => true, 
        'query_var' => true, 
        'rewrite' => true, 
        'capability_type' => 'post', 
        'hierarchical' => false, 
        'menu_position' => null, 
        'supports' => 
            array(
                'title',
                'editor',
                'thumbnail'
            ) 
    );
	
    register_post_type(__( 'portfolio' ),$args);
    
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 56, 56, true );
    add_image_size( 'portfolio', 150, 150, true ); 
		
} 
function zephyr_portfolio0_columnfilter($columns){
    $thumb = array('thumbnail' => 'Images');
    $columns = array_slice($columns, 0, 2) + $thumb + array_slice($columns,1,null);
    return $columns;
}

function zephyr_portfolio1_columns($columns) {
    $thumb = array('category' => 'Categories');
    $columns = array_slice($columns, 0, 3) + $thumb + array_slice($columns,1,null);
    return $columns;
}
function zephyr_portfolio1_column($column){
  global $post;
  if( $column == 'category' ){
    $terms = get_the_terms($post_id, 'filter');
        if ( !empty( $terms ) ){
            $out = array();
            foreach ( $terms as $term )
                $out[] = esc_html(sanitize_term_field('name', $term->name, $term->term_id, 'foo_skill', 'display'));
            echo join( ', ', $out );
        }else{
            echo 'No Category.';  //No Taxonomy term defined
        }
  }
}
function zephyr_portfolio_messages($messages){
    $messages[__( 'portfolio' )] = 
        array(
            0 => '',			
            1 => sprintf(__('Portfolio Updated. <a href="%s">View portfolio</a>'), esc_url(get_permalink($post_ID))),			
            2 => __('Custom Field Updated.'),			
            3 => __('Custom Field Deleted.'),			
            4 => __('Portfolio Updated.'),			
            5 => isset($_GET['revision']) ? sprintf( __('Portfolio Restored To Revision From %s'), wp_post_revision_title((int)$_GET['revision'],false)) : false,			
            6 => sprintf(__('Portfolio Published. <a href="%s">View Portfolio</a>'), esc_url(get_permalink($post_ID))),			
            7 => __('Portfolio Saved.'),			
            8 => sprintf(__('Portfolio Submitted. <a target="_blank" href="%s">Preview Portfolio</a>'), esc_url( add_query_arg('preview','true',get_permalink($post_ID)))),			
            9 => sprintf(__('Portfolio Scheduled For: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Portfolio</a>'),date_i18n( __( 'M j, Y @ G:i' ),strtotime($post->post_date)), esc_url(get_permalink($post_ID))),			
            10 => sprintf(__('Portfolio Draft Updated. <a target="_blank" href="%s">Preview Portfolio</a>'), esc_url( add_query_arg('preview','true',get_permalink($post_ID)))),
        );
    return $messages;	
	
} 

function zephyr_portfolio_filter(){

    register_taxonomy(__( "filter" ), 

    array(__( "portfolio" )), 

    array(
        "hierarchical" => true, 
        "label" => __( "Filter" ), 
        "singular_label" => __( "Filter" ), 
        "rewrite" => array(
            'slug' => 'filter', 
            'hierarchical' => true
            )
        )
    ); 
} 
?>
