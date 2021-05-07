<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>




<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


    <div class="container-fluid container_single_pacchetti">
        <div class="row row_single_header justify-content-center">
            <div class="col-lg-8 col-offset-4 col-sm-12">
              <div class="col-12 titolo_pacchetto p-4">
                  <?php echo the_title(); ?>
              </div>
                <!--Carousel Wrapper-->
                <div id="carousel-thumb" class="carousel slide carousel-fade carousel-thumbnails" data-ride="carousel">
                    <!--Slides-->
                    <div class="carousel-inner" role="listbox">

                        <?php if ( $post->post_type == 'annunci' && $post->post_status == 'publish' ) {

                            $images = get_post_meta($post->ID, 'some_custom_gallery');

                            if(!empty($images)) {
                                foreach ($images as  $image) {
                                    $value[]=$image;
                                }
                            } else {
                                $value=get_post_thumbnail_id();
                            }

                            $attachments = get_posts( array(
                                'post_type' => 'attachment',
                                'posts_per_page' => -1,
                                'include' => implode($value)
                            ) );

                            if ( $attachments ) {
                                foreach ( $attachments as $attachment ) {

                                    $thumbimg = wp_get_attachment_url( $attachment->ID, 'full', true );

                                    ?>
                                    <div class="carousel-item juve">
                                        <img class="d-block w-100 single_pacchetti_img img-fluid" src="<?php echo  $thumbimg ?>" />
                                    </div>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function($){
                            $(".carousel-item:first-child").addClass("active");
                            $(".carousel-indicator:first-child").addClass("active");
                            $('.carousel-inner').each(function() {
                                if ($(this).children('div').length === 1) $(this).siblings('.carousel-indicators, .carousel-control-prev, .carousel-control-next').hide();
                            });
                        });
                    </script>
                    <!--/.Slides-->
                    <!--Controls-->
                    <a class="carousel-control-prev" href="#carousel-thumb" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Prev</span>
                    </a>
                    <a class="carousel-control-next" href="#carousel-thumb" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Nex</span>
                    </a>
                    <!--/.Controls-->
                    <ol class="carousel-indicators juve indicators_pacchetti">
                        <?php if ( $post->post_type == 'annunci' && $post->post_status == 'publish' ) {
                            $attachments = get_posts( array(
                                'post_type' => 'attachment',
                                'posts_per_page' => -1,
                                'include' => implode($value)
                            ) );

                            if ( $attachments ) {
                                $count = -1;
                                foreach ( $attachments as $attachment ) {
                                    $thumbimg_thumb = wp_get_attachment_url( $attachment->ID, 'thumbnail-size', true );
                                    $count ++;
                                    ?>
                                    <li data-target="#carousel-thumb" data-slide-to="<?php echo $count ?>" class="carousel-indicator active"> <img class="d-block w-100 thumb_pacchetti_indicators" src="<?php echo $thumbimg_thumb ?>"</li>
                                    <?php
                                }
                            }
                        }
                        ?>

                    </ol>
                </div>

                <!--/.Carousel Wrapper-->
            </div>
            </div>

            <div class="row row_single_content justify-content-center">
            <div class="col-lg-7 col-offset-3 col-sm-12">
                <div class="col-12 head_descrizione mt-4 mb-3">
                    <h3>Descrizione</h3>
                </div>
                <div class="line"></div>
                <div class="col-12 pacchetto_descrizione">
                    <?php echo the_excerpt(); ?>
                </div>

                <div class="col-12 info_aggiuntive">
                    <p><i class="fas fa-map-marker-alt"></i> <span class="agg"> Indirizzo: </span> <?php  echo $indirizzo = get_post_meta( $post->ID, 'indirizzo', true ); ?></p>
                    <p><i class="fas fa-home"></i><span class="agg"> Vani: </span> <?php echo $vani = get_post_meta( $post->ID, 'vani', true );?> vani</p>
                    <p><i class="fas fa-ruler-combined"></i><span class="agg"> Superficie: </span> <?php echo $superficie = get_post_meta( $post->ID, 'superficie', true );?> metri quadrati</p>
                    <p><i class="fas fa-star"></i><span class="agg"> Stato: </span> <?php echo $stato = get_post_meta( $post->ID, 'stato', true );?></p>
                    <p><i class="fas fa-arrow-up"></i><span class="agg"> Piano: </span> <?php echo $piano = get_post_meta( $post->ID, 'piano', true );?></p>
                    <p><i class="fas fa-key"></i><span class="agg"> Ingresso: </span> <?php echo $ingresso = get_post_meta( $post->ID, 'ingresso', true );?></p>
                    <p><i class="fas fa-temperature-high"></i><span class="agg"> Riscaldamento: </span> <?php echo $riscaldamento = get_post_meta( $post->ID, 'riscaldamento', true );?></p>
                    <p><i class="fas fa-leaf"></i><span class="agg"> Giardino: </span> <?php echo $giardino = get_post_meta( $post->ID, 'giardino', true );?></p>
                    <p><i class="fas fa-plug"></i><span class="agg"> A.P.E.: </span> <?php echo $ape = get_post_meta( $post->ID, 'ape', true );?></p>
                    <p><i class="fas fa-coins"></i><span class="agg"> Prezzo: </span> <?php  echo $prezzo = get_post_meta( $post->ID, 'prezzo', true ); ?> €</p>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 box_info google_map">
                    <div class="col-12 head_posizione mt-4 mb-3">
                        <h3>Posizione</h3>
                    </div>
                    <div id="map-container-google-1" class="z-depth-1-half map-container" style="height: 500px">
                        <iframe src="https://maps.google.com?q=<?php echo $posizione = get_post_meta( $post->ID, 'posizione', true );?>&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
            </div>
        </div>



    </div>

</article><!-- #post-## -->
