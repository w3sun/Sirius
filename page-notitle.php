<?php
/*
Template Name: 无标题页
*/
get_header();?>
<div id="main">
    <?php $site_banner = sirius_option('background_image');?>
    <?php if ( !empty( $site_banner ) ) {?>
    <div id="banner" style="background-image: url(<?php echo sirius_option('background_image'); ?>);"></div> 
    <?php } ?>
       <section>
            <div class="container">
				<?php if (have_posts()) : the_post(); update_post_caches($posts);
				?>
                    <div class="post-content"><?php the_content(); ?></div>
				<?php endif; ?>
                <?php comments_template(); ?>
            </div>
        </section>
</div>
<?php get_footer(); ?>