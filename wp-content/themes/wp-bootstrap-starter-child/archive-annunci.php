<?php
/**
 * Template Name: Archive Annunci
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

            <header class="page-header d-flex justify-content-center align-items-center header_annunci">
            <div class="col-12 header_contatti text-center mb-5 p-0">
                <img src="<?php echo get_stylesheet_directory_uri().'/images/living-room-2.jpg' ?>" >
                <div class="centered">

                <h1 class="page-title p-3">Tutti gli annunci</h1>
                </div>
            </div>

            </header><!-- .page-header -->

            <div class="row tot_annunci d-flex align-items-center">
                <div class="col-lg-3 col-sm-12 d-flex justify-content-center count_pacchetti pt-3">
                    Sono presenti <?php echo $count_posts = wp_count_posts( 'annunci' )->publish; ?> annunci per te
                </div>


            </div>

            <div class="row row_annunci row_reverse mt-3">



                <div class="col-lg-9 col-sm-12 annunci">
                    <div class="row cards">

                        <?php
                        /* Start the Loop */
                        while ( have_posts() ) : the_post();

                            /*
                             * Include the Post-Format-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                             */
                            get_template_part( 'template-parts/content-annunci', get_post_format() );

                        endwhile;

                        ?>
                        <div class="col-12 text-center p-3">
                            <?php the_posts_navigation(); ?>
                        </div>

                        <?php
                        else :

                            get_template_part( 'template-parts/content', 'none' );

                        endif; ?>
                    </div>



                </div>
                <div class="col-lg-3 col-sm-12 annunci_search">
                    <?php echo do_shortcode('[searchandfilter slug="annunci-form-2"]') ?>
                </div>

            </div><!-- #main -->
    </section><!-- #primary -->

<?php
if (wp_is_mobile()) {
    get_footer('mobile');

} else {
    get_footer();;
}
