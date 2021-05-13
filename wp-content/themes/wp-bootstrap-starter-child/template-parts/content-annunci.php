<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */



?>



<div class="col-lg-6 col-sm-12 box-news" id="post-<?php the_ID(); ?>" >
    <div class="card news_card m-3">
        <div class="card-body">
            <img class="card-img-top news_img" src="<?php echo the_post_thumbnail_url();  ?>" >
            <div class="box_news_date px-5 pt-5"><?php echo get_the_date('j F Y')  ?></div>
            <h5 class="px-5 font-weight-bold"><?php echo the_title(); ?></h5>
            <div class="card-text box_news_excerpt px-5"><?php echo the_excerpt(); ?></div>
            <div class="px-5 pb-5">
                <a class="btn btn-primary link_news" href="<?php echo the_permalink();  ?>">
                    Scopri di più
                </a>
            </div>
        </div>
    </div>

</div>


