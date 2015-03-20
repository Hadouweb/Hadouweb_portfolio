<?php
/*
Plugin Name: Zephyr portfolio
Description: Un léger plugin pour créer un portfolio
Version: 1.0
Author: Zephyr
License: GPL
*/ 

include 'inc/post_type.php'; 

add_action('init', 'post_portfolio_type');
add_action('init', 'zephyr_portfolio_filter', 0 );
add_filter('post_updated_messages', 'zephyr_portfolio_messages');
add_action('init', 'register_portfolio_js');
add_action('init', 'register_portfolio_styles');
add_action('manage_edit-portfolio_columns', 'zephyr_portfolio0_columnfilter');
add_action('manage_edit-portfolio_columns', 'zephyr_portfolio1_columns');
add_action('manage_posts_custom_column', 'zephyr_portfolio1_column');

function zephyr_portfolio_show() { ?>

<section id="options" class="clearfix">
    
<ul id="filters" class="option-set clearfix" data-option-key="filter">
    <li class="filter0 active">       
        <div class="mainlink">
        <div class="beforelink"></div>
        <a href="#filter" data-option-value="*" class="selected">Toutes</a>
        <div class="afterlink"></div>
        </div>   
    </li>

    <?php
        $terms = get_terms('filter', $args);
        $count = count($terms); 
        $i=0;
        if ($count > 0) {

            foreach ($terms as $term) {

                $i++;

                $term_list .= '<li class="filter'. $term->term_id .'"><div class="mainlink"><div class="beforelink"></div>
                <a href="#filter" data-option-value=".'. $term->slug .'">' . $term->name . '</a>                
                <div class="afterlink"></div></div></li>';

                if ($count != $i){
                        $term_list .= '';
                }else {
                        $term_list .= '';
                }
            }

            echo $term_list;
        }
    ?>
    
</ul>
</section> 

<div id="container" class="clearfix">
    <?php 

    $wpbp = new WP_Query(array( 'post_type' => 'portfolio', 'posts_per_page' => 30 ) ); 
    if ($wpbp->have_posts()) {
        while ($wpbp->have_posts())  {
            $wpbp->the_post(); 

            $terms = get_the_terms( get_the_ID(), 'filter' ); 

            $large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' ); 
            $large_image = $large_image[0]; 

            ?><div class="element <?php foreach ($terms as $term) { echo strtolower(preg_replace('/\s+/', '-', $term->name)). ' '; } ?>">

                <?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { ?>

                    <a rel="" class="fancybox" data-fancybox-group="gallery" href="<?php echo $large_image ?>"><?php the_post_thumbnail('portfolio'); ?><div class="imgportfolio"></div></a>									

                <?php } ?>	


            </div>

<?php   }
    }
}

function zephyr_portfolio_script() {
if ( !is_admin() ) { wp_deregister_script( 'jquery' ); }
    wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
    wp_register_script('isotope', plugins_url( '/js/jquery.isotope.min.js' , (__FILE__) ));
    /*wp_register_script('easing', plugins_url( '/js/jquery.easing.1.3.js' , (__FILE__) ));
    wp_register_script('mousewheel', plugins_url( '/js/jquery.mousewheel-3.0.6.pack.js' , (__FILE__) ));
    wp_register_script('fancybox', plugins_url( '/js/fancybox.js' , (__FILE__) ));
    wp_register_script('jquery_fancybox', plugins_url( '/js/jquery.fancybox.js' , (__FILE__) ));*/
    wp_register_script('zephyr_portfolio', plugins_url( '/js/zephyr_portfolio.js' , (__FILE__) ));

    wp_enqueue_script('jquery');
    wp_enqueue_script('isotope');
    /*wp_enqueue_script('mousewheel');*/
   /* wp_enqueue_script('easing');
    wp_enqueue_script('fancybox');
    wp_enqueue_script('jquery_fancybox');*/
    wp_enqueue_script('zephyr_portfolio');
}
add_action( 'wp_enqueue_scripts', 'zephyr_portfolio_script' );

function zephyr_portfolio_style(){

    wp_enqueue_style( 'style_portfolio', plugins_url( '/css/style.css' , (__FILE__) ));
    wp_enqueue_style( 'style_fancybox', plugins_url( '/css/jquery.fancybox.css' , (__FILE__)) );
}
add_action( 'wp_enqueue_scripts', 'zephyr_portfolio_style' );
?>

    
