<?php
/**
* Template Name: Template Annunci
 */

if ( wp_is_mobile() ) {
    get_header('mobile');


} else {
    get_header();

}


?>

	<section id="primary" class="content-area col-sm-12 p-0">
		<div id="main" class="site-main homepage" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'annunci' );

				// If comments are open or we have at least one comment, load up the comment template.
				// if ( comments_open() || get_comments_number() ) :
				// 	comments_template();
				// endif;


			endwhile; // End of the loop.
			?>


		</div><!-- #main -->


	</section><!-- #primary -->

<?php
if (wp_is_mobile()) {
    get_footer('mobile');

} else {
    get_footer();
}
