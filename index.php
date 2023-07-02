<?php get_header(); ?>

<section class="topPanel">
    <div class="container">
        <h1><?php echo is_home() ? get_the_title( BLOG_ID ) : get_the_archive_title(); ?></h1>
    </div>
</section>

<section class="posts__holder">
    <div class="container">
        <div class="posts__container">
            <?php if ( function_exists( 'load_posts_ajax' ) ) :
                load_posts_ajax();
            else :
                if ( have_posts() ) : while ( have_posts() ) : the_post();
                    get_template_part( 'tpl-parts/post-item' );
                endwhile; endif;
            endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
