<?php
$theme = isset( $_GET['theme'] ) && in_array( strtolower( $_GET['theme'] ), [ 'white', 'dark' ] ) ? strtolower( $_GET['theme'] ) : 'custom';
$custom_theme_style  = '../build/css/'. $theme .'.min.css?v=' . sha1( filemtime('../build/css/'. $theme .'.min.css') );
$custom_theme_script = '../build/js/custom.min.js?v=' . sha1( filemtime('../build/js/custom.min.js') );
# $custom_extended_script = '../src/js/visixx.js?v=' . sha1( filemtime('../src/js/visixx.js') );

include './visix_head.php';
?>
  <body class="nav-sm" id="body-content">
    <div class="container body">
      <div class="main_container">
<?php include './visix_sidebar.php'; ?>
<?php /* include './visix_topnavi.php'; */ ?>

<?php include './visix_content_canvasjs.php'; ?>

<?php include './visix_footer_canvasjs.php'; ?>
  </body>
</html>
