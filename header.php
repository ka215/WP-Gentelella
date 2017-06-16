<?php
/**
 * The Header for WP-Gentelella theme.
 *
 * @package WP-Gentelella
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="ie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 10)|!(IE)]><!--><html <?php language_attributes(); ?>> <!--<![endif]-->
  <head>
    <meta charset="<?= bloginfo('charset') ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title><?php bloginfo('name'); ?><?php if ( is_front_page() ){ echo ' - ' . get_bloginfo( 'description' ); } else echo wp_title(); ?></title>

    <!-- Meta -->
    <meta name="description" content="<?php /* echo wpgentelella_option( 'meta_description' ); */ ?>">
    <meta name="keywords" content="<?php /* echo wpgentelella_option( 'meta_keyword' ); */ ?>">
    <meta name="author" content="<?php /* echo wpgentelella_option( 'meta_author' ); */ ?>">

    <!-- Favicons -->
    <link rel="shortcut icon" href="<?php /* echo wpgentelella_option( 'favicon', false, 'url' ); */ ?>">
    <link rel="icon" type="image/png" href="<?php /* echo wpgentelella_option( 'favicon', false, 'url' ); */ ?>" />
    <link rel="apple-touch-icon" href="<?php /* echo wpgentelella_option( 'touch_icon', false, 'url' ); */ ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php /* echo wpgentelella_option( 'touch_icon_72', false, 'url' ); */ ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php /* echo wpgentelella_option( 'touch_icon_144', false, 'url' ); */ ?>">

    <!-- Bootstrap -->
    <!-- link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous" -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- link href="<?php echo WPGENT_DIR; ?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" -->
    <!-- Font Awesome -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <!-- link href="<?php echo WPGENT_DIR; ?>vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" -->
    <!-- NProgress -->
    <link href="<?php echo WPGENT_DIR; ?>vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo WPGENT_DIR; ?>build/css/custom.min.css?<?= base64_encode( hash( 'sha384', filemtime( WPGENT_PATH . '/build/css/custom.min.css' ), true ) ); ?>" rel="stylesheet">
    <!-- Fonts -->
    <?php echo setFont( 'ja', true, 400 ); ?>

    <?php wp_head(); ?>
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body <?php body_class( 'nav-md' ); ?>>
    <div class="container body">
      <div class="main_container">
