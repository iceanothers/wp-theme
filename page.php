<?php get_header();
$thumb_id = get_post_thumbnail_id( get_the_ID() );
?>

<section class="topPanel">
    <?php if ( has_post_thumbnail( $thumb_id ) ) { ?>
        <figure>
            <?php echo wp_get_attachment_image( $thumb_id, 'full', false, array( 'alt' => get_alt( $thumb_id ), 'class' => 'cover' ) ); ?>
        </figure>
    <?php } ?>
    <div class="container">
        <h1><?php the_title(); ?></h1>
    </div>
</section>

<section class="content">
    <div class="container">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; endif; ?>
    </div>
</section>

<?php get_footer(); ?>
