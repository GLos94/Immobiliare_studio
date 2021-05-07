<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */

?>
<?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>
			</div><!-- .row -->
		</div><!-- .container -->
	</div><!-- #content -->
    <?php get_template_part( 'footer-widget' ); ?>
	<footer id="colophon" class="site-footer <?php echo wp_bootstrap_starter_bg_class(); ?>" role="contentinfo">
		<div class="container pt-3 pb-3">
            <div class="row py-4">
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0"><img src="<?php echo get_stylesheet_directory_uri().'/images/logo.png' ?>" alt="" width="180" class="mb-3"></div>
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h6 class="text-uppercase font-weight-bold mb-4"><i class="fas fa-map-marker-alt"></i> Vieni a trovarci</h6>
                    <p class="text-muted">Corso Aldo Moro, 63 - Orta Nova (FG)</p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h6 class="text-uppercase font-weight-bold mb-4"><i class="fas fa-phone"></i> Chiamaci</h6>
                    <p class="text-muted">Tel/Fax: 0885 782861 <br>
                    Cellulare: 393 1182148</p>
                </div>
                <div class="col-lg-4 col-md-6 mb-lg-0">
                    <h6 class="text-uppercase font-weight-bold mb-4"><i class="fas fa-envelope"></i> Contattaci</h6>
                    <p class="text-muted mb-4"><a href="mailto:info@studiocasaortanova.it">info@studiocasaortanova.it</a></p>
                </div>
            </div>
        </div>

        <!-- Copyrights -->
        <div class="bg-light py-3">
            <div class="container text-center">
                <p class="text-muted mb-0 py-2">© <?php echo date('Y'); ?> <?php echo '<a href="'.home_url().'">'.get_bloginfo('name').'</a>'; ?> - Tutti i diritti riservati</p>
            </div>
		</div>

	</footer><!-- #colophon -->
<?php endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>