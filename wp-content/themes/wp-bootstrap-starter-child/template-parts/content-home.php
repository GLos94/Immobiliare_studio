<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */



$the_query = new WP_Query( array(
    'post_type' => 'annunci',
    'post_status' => 'publish',
    'order' => 'ASC',
    'posts_per_page' => 4


) );


while ( $the_query->have_posts() ) :  $the_query->the_post();

endwhile;


wp_reset_postdata();



$the_category_query = new WP_Query( array(
    'post_type' => 'annunci',
    'tax_query' => array(
        array (
            'taxonomy' => 'categorie_annuncio',
            'field' => 'slug',
            'terms' => 'vendita', 'affitto',

        )
    )


) );


while ( $the_category_query->have_posts() ) :  $the_category_query->the_post();

endwhile;


wp_reset_postdata();
?>



	<div class="container-fluid p-0">
	    <div class="row row_introduction">
            <div class="col-12">
            <img src="<?php echo get_stylesheet_directory_uri().'/images/skyline.jpg' ?>" >
                <div class="centered">
                    <div class="row">
                        <div class="col-12 home_search_form">
                            <?php echo do_shortcode('[searchandfilter id="453"]') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="presentation" class="row row_presentation mb-5 px-3">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-4 col-sm-12 chi_siamo text-center p-5">
                        <i class="fas fa-hands-helping"></i>
                        <h6 class="presentation_title mt-3">Chi siamo</h6>
                        <p class="presentation_txt p-3">Studio Casa Immobiliare nasce nel 2000 come agenzia operante nell'intermediazione immobiliare e
                            di consulenza creditizia al fine di ottenere soluzioni personalizzate e trasparenti per ogni tipo di esigenza.
                            Un marchio che è diventato ben presto sinonimo di professionalità, affidabilità e riservatezza.
                        </p>
                    </div>
                    <div class="col-lg-4 col-sm-12 servizi text-center p-5">
                        <i class="fas fa-home"></i>
                        <h6 class="presentation_title mt-3">I nostri servizi</h6>
                        <p class="presentation_txt p-3">Offriamo una consulenza personalizzata,
                            per l'acquisto/vendita e la locazione per qualsiasi immobile di tipo residenziale, commerciale e industriale;
                            oltre che di terreni sia agricoli che edificabili. Ogni nostra scelta è caratterizzata da grande serietà e continuità.
                        </p>
                    </div>
                    <div class="col-lg-4 col-sm-12 assistenza text-center p-5">
                        <i class="fas fa-suitcase"></i>
                        <h6 class="presentation_title mt-3">Assistenza</h6>
                        <p class="presentation_txt p-3">Assistiamo il cliente con professionalità
                            sino alla sottoscrizione definitiva dell'atto di compravendita: creando le giuste sinergie con i migliori istituti di credito;
                            accompagnandolo anche nella scelta del notaio e nella gestione dei rapporti burocratici con quest'ultimo.

                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row_annunci p-5">
            <div class="col-12 text-center pb-5">
                <h6>IN VETRINA</h6>
            </div>
            <div class="col-12">
                <div class="row">
                    <?php while ( $the_query->have_posts() ) :  $the_query->the_post(); ?>
                        <div class="col-md-3">
                            <div class="card mb-2">
                                <img class="card-img-top" src="<?php echo the_post_thumbnail_url(); ?>"
                                     alt="Card image cap">
                                <div class="card-body">
                                    <p class="card-title font-weight-bold"><?php echo the_title(); ?></p>
                                    <a href="<?php the_permalink(); ?>" class="btn btn-info">Vedi dettagli</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="col-12 text-center p-4"><a href="<?php echo site_url('/annunci'); ?>" class="btn btn-secondary">Vedi tutti gli annunci</a></div>
        </div>

        <div class="row row_valutazione">
            <!-- shortcode form-->
        </div>

	</div>
