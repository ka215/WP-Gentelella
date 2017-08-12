<?php
$custom_theme_style  = '../build/css/custom.min.css?v=' . sha1( filemtime('../build/css/custom.min.css') );
$custom_theme_script = '../build/js/custom.min.js?v=' . sha1( filemtime('../build/js/custom.min.js') );

include './visix_head.php';
?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
<?php include './visix_sidebar.php'; ?>
<?php include './visix_topnavi.php'; ?>

<?php include './visix_content.php'; ?>

<?php include './visix_footer.php'; ?>
  </body>
</html>
