<?php
$theme = isset( $_GET['theme'] ) && in_array( strtolower( $_GET['theme'] ), [ 'white', 'dark' ] ) ? strtolower( $_GET['theme'] ) : 'custom';
$custom_theme_style  = '../build/css/'. $theme .'.min.css?v=' . sha1( filemtime('../build/css/'. $theme .'.min.css') );
$custom_theme_script = '../build/js/custom.min.js?v=' . sha1( filemtime('../build/js/custom.min.js') );
# $custom_extended_script = '../src/js/vizixx.js?v=' . sha1( filemtime('../src/js/vizixx.js') );
$demo_chart_script   = '../build/js/demo-chart.js?v=' . sha1( filemtime('../build/js/demo-chart.js') );

require_once './get_text.php';

include './vizixx_head.php';
?>
  <body class="nav-md" id="body-content">
    <div class="container body">
      <div class="main_container">
<?php include './vizixx_sidebar.php'; ?>
<?php include './vizixx_topnavi.php'; ?>

<?php include './vizixx_content_chartjs.php'; ?>

<?php include './vizixx_footer_chartjs.php'; ?>
  </body>
</html>
