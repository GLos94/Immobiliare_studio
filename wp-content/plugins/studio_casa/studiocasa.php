<?php

/**
* Plugin Name: Studio Casa Plugin
* Plugin URI:  https://studiocasaimmobiliare.com/
* Description: Studio Casa Plugin
* Author:      Giulia Losito
* Author URI:
* Version:     1.0.0
* Text Domain: studio_casa
* Domain Path: /languages/
*/

wp_enqueue_style('style-css', get_stylesheet_directory_uri() . '/style.css', true);

// ------ creazione post-type ANNUNCIO

add_action( 'init', 'ad_register_post_type' );
function ad_register_post_type() {
$labels = array(
'name' => __( 'Annunci', '' ),
'singular_name' => __( 'Annuncio', '' ),
'add_new' => __( 'New Annuncio', '' ),
'add_new_item' => __( 'Add New Annuncio', '' ),
'edit_item' => __( 'Edit Annuncio', '' ),
'new_item' => __( 'New Annuncio', '' ),
'view_item' => __( 'View Annunci', '' ),
'search_items' => __( 'Search Annunci', '' ),
'not_found' =>  __( 'No Annunci Found', '' ),
'not_found_in_trash' => __( 'No Annunci found in Trash', '' ),
);
$args = array(
'labels' => $labels,
'has_archive' => true,
'public' => true,
'hierarchical' => true,
'description' => true,
'supports' => array(
'title',
'editor',
'thumbnail',
'page-attributes'
),
'rewrite'   => array( 'slug' => 'annunci' ),
'show_in_rest' => true
);

register_post_type( 'annunci', $args );
}


// ------ creazione tassonomie per ANNUNCIO
add_action( 'init', 'annunci_taxonomy');
function annunci_taxonomy() {
    register_taxonomy(
        'categorie_annuncio',  // The name of the taxonomy
        'annunci',             // post type name
        array(
            'has_archive' => true,
            'hierarchical' => true,
            'label' => 'Categorie Annuncio', // display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'categorie_annuncio',
                'with_front' => false
            )
        )
    );

}

add_action( 'add_meta_boxes', 'annuncio_info_metabox' );
function annuncio_info_metabox (){

    add_meta_box(
        'annuncio-info',
        'Informazioni Aggiuntive',
        'annuncio_info_metabox_callback',
        'annunci',
        'normal',
        'default'
    );

}

// callback info lista regalo
function annuncio_info_metabox_callback($post){

    $indirizzo = get_post_meta($post->ID, 'indirizzo', true);
    $vani = get_post_meta($post->ID, 'vani', true);
    $superficie = get_post_meta($post->ID, 'superficie', true);
    $stato = get_post_meta($post->ID, 'stato', true);
    $piano = get_post_meta($post->ID, 'piano', true);
    $ingresso = get_post_meta($post->ID, 'ingresso', true);
    $riscaldamento = get_post_meta($post->ID, 'riscaldamento', true);
    $giardino = get_post_meta($post->ID, 'giardino', true);
    $ape = get_post_meta($post->ID, 'ape', true);
    $prezzo = get_post_meta($post->ID, 'prezzo', true);
    $posizione = get_post_meta($post->ID, 'posizione', true);


    ?>


    <div class="form-group info_annuncio">
        <label for="indirizzo"><i class="fas fa-map-marker-alt"></i> Indirizzo</label>
        <input id="indirizzo" class="form-control" type="text" name="indirizzo" value="<?php echo $indirizzo; ?>">
    </div>
    <div class="form-group info_annuncio">
        <label for="vani"><i class="fas fa-home"></i> Vani</label>
        <input id="vani" class="form-control" type="number" name="vani" value="<?php echo $vani; ?>">
    </div>
    <div class="form-group info_annuncio">
        <label for="superficie"><i class="fas fa-ruler-combined"></i> Superficie</label>
        <input id="superficie" class="form-control" type="number" name="superficie" value="<?php echo $superficie ?>">
    </div>
    <div class="form-group info_annuncio">
        <label for="piano"><i class="fas fa-arrow-up"></i> Piano</label>
        <input id="piano" class="form-control" type="text" name="piano" value="<?php echo $piano ?>">
    </div>
    <div class="form-group info_annuncio">
        <label for="stato"><i class="fas fa-star"></i> Stato</label>
        <input id="stato" class="form-control" type="text" name="stato" value="<?php echo $stato ?>">
    </div>
    <div class="form-group info_annuncio">
        <label for="ingresso"><i class="fas fa-key"></i> Ingresso</label>
        <input id="ingresso" class="form-control" type="text" name="ingresso" value="<?php echo $ingresso ?>">
    </div>
    <div class="form-group info_annuncio">
        <label for="riscaldamento"><i class="fas fa-temperature-high"></i> Riscaldamento</label>
        <input id="riscaldamento" class="form-control" type="text" name="riscaldamento" value="<?php echo $riscaldamento ?>">
    </div>
    <div class="form-group info_annuncio">
        <label for="giardino"><i class="fas fa-leaf"></i> Giardino</label>
        <input id="giardino" class="form-control" type="text" name="giardino" value="<?php echo $giardino ?>">
    </div>
    <div class="form-group info_annuncio">
        <label for="ape"><i class="fas fa-plug"></i> A.P.E.</label>
        <input id="ape" class="form-control" type="text" name="ape" value="<?php echo $ape ?>">
    </div>
    <div class="form-group info_annuncio">
        <label for="prezzo"><i class="fas fa-euro-sign"></i> Prezzo </label>
        <input id="prezzo" class="form-control" type="text"  name="prezzo" value="<?php echo $prezzo; ?>">
    </div>
    <div class="form-group info_annuncio">
        <label for="posizione"><i class="fas fa-map-pin fa-lg"></i> Posizione (inserire latitudine e longitudine)</label>
        <input id="posizione" class="form-control" type="text"  name="posizione" value="<?php echo $posizione; ?>">
    </div>

    <?php

}


// save info lista regalo
add_action( 'save_post', 'lista_info_save_meta' );
function lista_info_save_meta($post_id) {

    if ( !current_user_can( 'edit_post', $post_id ))
        return;

    if( isset( $_POST[ 'indirizzo' ] ) ) {
        update_post_meta( $post_id, 'indirizzo', sanitize_text_field( $_POST[ 'indirizzo' ] ) );
    }

    if( isset( $_POST[ 'vani' ] ) ) {
        update_post_meta( $post_id, 'vani', intval( $_POST[ 'vani' ] ) );
    }

    if( isset( $_POST[ 'superficie' ] ) ) {
        update_post_meta( $post_id, 'superficie', intval( $_POST[ 'superficie' ] ) );
    }

    if( isset( $_POST[ 'stato' ] ) ) {
        update_post_meta( $post_id, 'stato', sanitize_text_field( $_POST[ 'stato' ] ) );
    }

    if( isset( $_POST[ 'piano' ] ) ) {
        update_post_meta( $post_id, 'piano', sanitize_text_field( $_POST[ 'piano' ] ) );
    }

    if( isset( $_POST[ 'ingresso' ] ) ) {
        update_post_meta( $post_id, 'ingresso', sanitize_text_field( $_POST[ 'ingresso' ] ) );
    }

    if( isset( $_POST[ 'riscaldamento' ] ) ) {
        update_post_meta( $post_id, 'riscaldamento', sanitize_text_field( $_POST[ 'riscaldamento' ] ) );
    }


    if( isset( $_POST[ 'giardino' ] ) ) {
        update_post_meta( $post_id, 'giardino', sanitize_text_field( $_POST[ 'giardino' ] ) );
    }

    if( isset( $_POST[ 'ape' ] ) ) {
        update_post_meta( $post_id, 'ape', sanitize_text_field( $_POST[ 'ape' ] ) );
    }

    if( isset( $_POST[ 'prezzo' ] ) ) {
        update_post_meta( $post_id, 'prezzo', sanitize_text_field( $_POST[ 'prezzo' ] ) );
    }

    if( isset( $_POST[ 'posizione' ] ) ) {
        update_post_meta( $post_id, 'posizione', sanitize_text_field( $_POST[ 'posizione' ] ) );
    }

}


// ------ SEZIONE GALLERIA

add_action( 'admin_enqueue_scripts', 'misha_scripts_for_gallery' );
function misha_scripts_for_gallery(){
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-widget');
    wp_enqueue_script('jquery-ui-sortable');

    if ( ! did_action( 'wp_enqueue_media' ) )
        wp_enqueue_media();

    wp_enqueue_script('my_scripts', plugins_url('/studio_casa/js/custom-gallery.js'), array('jquery','jquery-ui-sortable') );
}


function misha_gallery_field( $name, $value = '' ) {

    $html = '<div class="container"><div class="row misha_gallery_mtb gallery_row_pacchetti">';
    /* array with image IDs for hidden field */
    $hidden = array();


    if( $images = get_posts( array(
        'post_type' => 'attachment',
        'orderby' => 'post__in', /* we have to save the order */
        'order' => 'ASC',
        'post__in' => explode(',',$value), /* $value is the image IDs comma separated */
        'numberposts' => -1,
        'post_mime_type' => 'image'
    ) ) ) {

        foreach( $images as $image ) {
            $hidden[] = $image->ID;
            $image_src = wp_get_attachment_image_src( $image->ID, array( 80, 80 ) );
            $html .= '<div class="col-3 gallery_pacchetti_item" style="background-image:url(' . $image_src[0] . ');width: 100%;height: 200px;background-repeat: no-repeat;display: inline-block" data-id="' . $image->ID .  '"><a href="#" class="misha_gallery_remove"><i class="fas fa-trash"></i></a></div>';
        }

    }

    $html .= '</div></div>';
    $html .= '<input type="hidden" name="'.$name.'" value="' . join(',',$hidden) . '" /><a href="#" class="button misha_upload_gallery_button mt-2"><i class="fas fa-plus-circle"></i> Aggiungi immagini</a>';

    return $html;
}


/*
 * Add a meta box
 */
add_action( 'add_meta_boxes', 'misha_meta_box_add' );

function misha_meta_box_add() {
    add_meta_box('mishadiv', // meta box ID
        'Galleria', // meta box title
        'misha_print_box', // callback function that prints the meta box HTML
        'annunci', // post type where to add it
        'normal',// priority
        'default' );

}

/*
 * Meta Box HTML
 */
function misha_print_box( $post ) {
    $meta_key = 'some_custom_gallery';
    echo misha_gallery_field( $meta_key, get_post_meta($post->ID, $meta_key, true) );
}

/*
 * Save Meta Box data
 */
add_action('save_post', 'misha_save');

function misha_save( $post_id ) {
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post_id;

    $meta_key = 'some_custom_gallery';

    update_post_meta( $post_id, $meta_key, $_POST[$meta_key] );

    return $post_id;
}

// ------ FINE GALLERIA
