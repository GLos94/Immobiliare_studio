<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_style' );
function enqueue_parent_theme_style() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

function fontawesome_dashboard() {
    wp_enqueue_style('fontawesome', 'https://use.fontawesome.com/releases/v5.8.1/css/all.css', '', '5.8.1', 'all');
}

add_action('admin_init', 'fontawesome_dashboard');

/**
 *
Filter the except length to 20 words.
 *
 *
@param int $length Excerpt length.
 *
@return int (Maybe) modified excerpt length.
 */
function wpdocs_custom_excerpt_length(
    $length )
{
    return 20;
}
add_filter(
    'excerpt_length',
    'wpdocs_custom_excerpt_length',
    999 );