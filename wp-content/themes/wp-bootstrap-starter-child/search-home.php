<?php
/**
* Template Name: Archive Pacchetti
*/

if ( wp_is_mobile() ) {
    get_header('mobile');

} else {
    get_header();
}
?>

    <section id="primary" class="content-area col-12">
        <div id="main" class="site-main" role="main">

            <?php
            if ( have_posts() ) : ?>

                <header class="page-header d-flex justify-content-center align-items-center header_annunci p-3">
                    <h1 class="page-title">Risultati per te:</h1>
                </header><!-- .page-header -->
                  <div class="row row_annunci mt-3">
                    <div class="col-lg-12 col-sm-12 annunci">
                        <div class="row cards">

                          <?php
                          /* Start the Loop */
                          while ( have_posts() ) : the_post();

                              /*
                               * Include the Post-Format-specific template for the content.
                               * If you want to override this in a child theme, then include a file
                               * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                               */
                              get_template_part( 'template-parts/content-annunci-form', get_post_format() );

                          endwhile;

                          the_posts_navigation();

                      else :

                          get_template_part( 'template-parts/content', 'none' );

                      endif; ?>
                    </div>

                </div>

        </div><!-- #main -->
    </section><!-- #primary -->

<?php
if (wp_is_mobile()) {
    get_footer('mobile');

} else {
    get_footer();;
}
