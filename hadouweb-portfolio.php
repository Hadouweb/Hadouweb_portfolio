<?php
/**
 * Plugin Name: Hadouweb Portfolio Isotope
 * Plugin URI: hadouweb.fr
 * Description: Un portfolio dynamique avec Isotope et Fancybox.
 * Version: 0.3
 * Author: Nicolas Le Breton
 * Author URI: http://hadouweb.fr
 * License: GPL2
 */

function custom_post_type() {
    $labels = array(
        'name'                => ( 'Portfolio' ), // Le nom de mon menu
        'singular_name'       => ( 'Portfolio' ),
        'all_items'           => ( 'Tous les projets' ),
        'view_item'           => ( 'Voir le projet' ),
        'add_new_item'        => ( 'Ajouter un projet' ),
        'add_new'             => ( 'Ajouter' ),
        'edit_item'           => ( 'Editer un projet' ),
        'update_item'         => ( 'Mettre à jour' ),
        'search_items'        => ( 'Rechercher un projet' ),
        'not_found'           => ( 'Aucun résultat' ),
        'not_found_in_trash'  => ( 'Aucun résultat dans la corbeille' )
    );
    $args = array(
        'labels'              => $labels,
        'supports'            => array('title', 'thumbnail' ), // Permet de définir les éléments à ajouter pour notre type de contenu.
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true, // Pour l'ajouter dans la barre d'admin en haut dans l'onglet "Créer"
        'menu_position'       => 2, // L'ordre d'affichage dans le menu à gauche
        'menu_icon'           => 'dashicons-format-gallery', // Nom de l’icône
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page', // Permet de spécifier que l'utilisateur possède les mêmes droits qu'il a sur les pages
    );
    register_post_type( 'portfolio', $args );

}
add_action( 'init', 'custom_post_type', 0 );

function portfolio_category() {
    register_taxonomy(
        'project-cat',
        'portfolio',
        array(
            'label' => __( 'Catégories' ),
            'rewrite' => array( 'slug' => 'project-cat' ),
            'hierarchical' => true,
        )
    );
}
add_action( 'init', 'portfolio_category' );

function portfolio_scripts() {
    // Scripts
    wp_enqueue_script( 'isotope',  plugins_url() . '/hadouweb-portfolio/js/isotope.min.js', array(), '2.0.0', true );
    wp_enqueue_script( 'portfolio-script',  plugins_url() . '/hadouweb-portfolio/js/portfolio.js', array(), '0.1', true );
    wp_enqueue_script( 'fancybox-script',  plugins_url() . '/hadouweb-portfolio/js/fancybox.min.js', array(), '2.1.5', true );

    // Styles
    wp_enqueue_style( 'portfolio-style',  plugins_url() . '/hadouweb-portfolio/css/portfolio.css' );
    wp_enqueue_style( 'fancybox-style',  plugins_url() . '/hadouweb-portfolio/css/fancybox.css' );
}
add_action( 'wp_enqueue_scripts', 'portfolio_scripts' );


function hafolio_func( $atts ) {


    $a = shortcode_atts( array(
        'cat' => '',
        'nbr' => '',
    ), $atts, 'hafolio' );

    $cats = explode( ',', $a['cat'] );
    $nbr = $a['nbr'];

    if (!empty($cats)) {
        foreach ($cats as $cat ) {
            $filters[$cat] = get_term_by('slug', $cat, 'project-cat');
        }
    }else{
        $filters = get_terms( 'project-cat' );
    }

    if (empty($nbr)) {
        $nbr = '-1';
    }

    $result = '<div id="filters" class="button-group">
                    <button class="button is-checked" data-filter="*">Tous</button>';

    foreach ($cats as $cat ) {
        $result .= '<button class="button" data-filter=".'.$filters[$cat]->slug.'">'.$filters[$cat]->name.'</button>';
    }

    $result .= '</div>';

    $result .= '<div class="isotope">';

    $args = array (
        'post_type' => 'portfolio',
        'posts_per_page' => $nbr
    );

    $query = new WP_Query( $args );

        if ( $query->have_posts() ) :

            while ( $query->have_posts() ) : $query->the_post();

                $url = wp_get_attachment_url( get_post_thumbnail_id($query->post->ID) );
                $filter = wp_get_post_terms( $query->post->ID, 'project-cat');

                $result .= '<div class="element-item transition '.$filter[0]->slug.' data-category="transition">
                                    <a class="fancybox" rel="all" href="'.$url.'">
                                        '.get_the_post_thumbnail($query->post->ID, 'medium').'
                                    </a>
                                </div>';

            endwhile;

            wp_reset_postdata();

            $result .= '</div>';

        endif;

    return $result;
}
add_shortcode( 'hafolio', 'hafolio_func' );
?>