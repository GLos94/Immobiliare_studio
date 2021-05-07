<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */


?>



<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="col-12 header_contatti text-center mb-5 p-0">
        <img src="<?php echo get_stylesheet_directory_uri().'/images/diary.jpg' ?>" >
        <div class="centered">
            <h1>Contatti</h1>
        </div>
    </div>

    <div class="row row_form m-0 p-lg-5">
        <div class="col-lg-2 col-sm-12">
            <div class="col-12">

            </div>
        </div>
        <div class="col-lg-5 col-sm-12 form_contatti">

            <div class="row m-0">
                <div class="col-lg-6 col-sm-12 contatti_gravity p-4 d-flex justify-content-center">
                    <?php echo do_shortcode('[contact-form-7 id="46" title="Modulo di contatto 1"]') ?>
                </div>
                <div class="col-lg-6 col-sm-12 p-0 google_map_contatti">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2996.1901942069953!2d15.702224515669213!3d41.32647740773132!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1339d216bfc2e5a7%3A0xc16d09c752796e24!2sAgenzia%20Immobiliare%20STUDIO%20CASA%20a%20Orta%20Nova%20(fg)!5e0!3m2!1sit!2sit!4v1618935362408!5m2!1sit!2sit" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-sm-12 mt-5 p-5">
            <div class="col-12 help">
                <h1>Hai bisogno di informazioni?</h1>
                <p>Contattaci compilando il form e inviando la tua richiesta.
                    Ti risponderemo il prima possibile</p>
            </div>
            <div class="col-12 vieni_in_agenzia">
                <h1>Vuoi passare a trovarci in Agenzia?</h1>
                <p>Chiamaci per prenotare il tuo appuntamento</p>
            </div>
            <div class="col-12">
                <div class="row m-0">
                    <div class="col-12 info_contatti p-0">
                        <p><i class="fas fa-map-marked"></i> Corso Aldo Moro, 63 - Orta Nova </p>
                        <p><i class="fas fa-phone-alt"></i> 0885 782861</p>
                        <p><i class="fas fa-mobile"></i>  393 1182148</p>
                        <p><i class="far fa-envelope"></i> info@studiocasaortanova.it</p>
                    </div>
                </div>
                <div class="row m-0">
                    <div class="col-12 info_orari p-0">
                        <p>Lunedi - Venerdì</p>
                        <p>09:30 - 12:30</p>
                        <p>17:00 - 20:30</p>
                    </div>
                </div>
            </div>
        </div>
    </div>





</div>




