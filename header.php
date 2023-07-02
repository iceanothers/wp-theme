<?php
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
header('Content-Type: text/html; charset=utf-8');
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header('X-UA-Compatible: IE=Edge');
if( @!WP_DEBUG) { ob_start('ob_html_compress'); }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php wpa_title(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=5.0"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="format-detection" content="telephone=no">
        <meta name="theme-color" content="#1c2c39">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,500&display=swap"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,500&display=swap" media="print" onload="this.media='all'"/>
        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?> data-a="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
        <div id="main">
            <header>
                <div class="container alc">
                    <?php echo ( is_front_page() ) ? '<figure id="logo">' : '<a href="'. esc_url( get_site_url() ) .'" id="logo">'; ?>
                        <img src="<?php echo theme('images/logo.png'); ?>" alt="logo">
                    <?php echo ( is_front_page() ) ? '</figure>' : '</a>'; ?>
                    <div id="menuOpen"><span></span></div>
                    <div class="menuBox">
                        <div class="menuContent flex">
                            <nav id="mainMenu">
                                <?php wp_nav_menu(array('container' => false, 'items_wrap' => '<ul id="%1$s">%3$s</ul>', 'theme_location'  => 'main_menu')); ?>
                            </nav>
                            <?php echo wp_kses_post( so_me() ); ?>
                        </div>
                    </div>
                </div>
            </header>