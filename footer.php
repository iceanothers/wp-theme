</div>

<footer>
    <div class="container flex">
        <div class="copyright">© <?php bloginfo(); ?> <?php echo esc_html( date( 'Y' ) ); ?></div>
        <?php echo wp_kses_post( so_me() ); ?>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>