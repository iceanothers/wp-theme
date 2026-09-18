<?php get_header(); ?>

<section class="top_panel">
    <div class="container">
        <h1><span>Search Results</span></h1>
    </div>
</section>

<section class="search_page">
    <div class="container">
        <div class="search_results">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
                get_template_part( 'tpl-parts/post-item' );
            endwhile; else : ?>
                <div><h3 class="custom_coming_soon">Oops! Nothing found.</h3></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
