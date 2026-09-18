<?php get_header(); ?>

<section class="topPanel">
    <div class="container">
        <h1><?php echo is_home() ? get_the_title( BLOG_ID ) : get_the_archive_title(); ?></h1>
    </div>
</section>

<section class="content">
    <div class="container">
        <?php get_template_part( 'tpl-parts/posts-filters' ); ?>

        <div class="posts__container show_box">
            <?php
            if ( function_exists( 'render_posts_ajax' ) ) {
                render_posts_ajax();
            } elseif ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    get_template_part( 'tpl-parts/post-item' );
                endwhile;
            endif;
            ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
