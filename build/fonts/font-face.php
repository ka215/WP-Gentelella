<?php
//if ( strpos( $_SERVER['HTTP_REFERER'], 'plotter.me/' ) !== false && ! empty( $_GET['l'] ) ) {
if ( ! empty( $_GET['l'] ) ) {
  $_p = explode( ';', $_GET['l'] );
  $current_language = $_p[0];
  $is_serif = intval( $_p[1] ) == 1 ? true : false;
  $base_weight = intval( $_p[2] );

  $global_import = '';
  $inline_import = '';
  $css_lines = [];
  switch ( $current_language ) {
    case 'ja':
      if ( $is_serif ) {
        $font_families = [
          '"Noto Serif Japanese"',
          '"游明朝"',
          'YuMincho',
          '"ヒラギノ明朝 ProN W3"',
          '"Hiragino Mincho ProN"',
          '"HG明朝E"',
          '"ＭＳ Ｐ明朝"',
          '"ＭＳ 明朝"',
          'serif'
        ];
        $local_font = 'Noto Serif CJK JP';
        $font_family = implode( ', ', $font_families );
        $css_lines[] = "  src: local(\"$local_font\"),";
        $inline_import = substr( implode( "\n", $css_lines ), 0, -1 ) . ";\n";
      } else {
        $font_families = [
          '"Noto Sans CJK JP"',
          '"Noto Sans JP"',
          'sans-serif',
        ];
        $local_font = 'Noto Sans CJK JP';
        $font_sizes = [
          100 => 'Thin',
          200 => 'ExtraLight',
          300 => 'Light',
          400 => 'Regular',
          500 => 'Medium',
          600 => 'SemiBold',
          700 => 'Bold',
          800 => 'SemiBlack',
          900 => 'Black'
        ];
        $webfont_paths = [
          'woff2'    => '//fonts.gstatic.com/ea/notosansjp/v5/NotoSansJP-'. $font_sizes[$base_weight] .'.woff2',
          'woff'     => '//fonts.gstatic.com/ea/notosansjp/v5/NotoSansJP-'. $font_sizes[$base_weight] .'.woff',
          'opentype' => '//fonts.gstatic.com/ea/notosansjp/v5/NotoSansJP-'. $font_sizes[$base_weight] .'otf',
        ];
        $font_family = implode( ', ', $font_families );
        $css_lines[] = "  src: local(\"$local_font\"),";
        foreach ( $webfont_paths as $_k => $_v ) {
          $css_lines[] = "    url($_v) format('$_k'),";
        }
        //$inline_import = substr( implode( "\n", $css_lines ), 0, -1 ) . ";\n";
      }
      break;
    default:
      if ( $is_serif ) {
        $global_import = "@import url('https://fonts.googleapis.com/css?family=Noto+Serif');\n";
        $font_families = [
          '"Noto Serif"',
          'serif',
        ];
        $font_family = implode( ', ', $font_families );
      } else {
        $global_import = "@import url('https://fonts.googleapis.com/css?family=Noto+Sans');\n";
        $font_families = [
          '"Noto Sans"',
          '"Helvetica Neue"',
          'Roboto',
          'Arial',
          '"Droid Sans"',
          'sans-serif',
        ];
        $font_family = implode( ', ', $font_families );
      }
      $css = implode( "\n", $css_lines );
      break;
  }

}
header( 'Content-Type: text/css; charset=utf-8' ); ?>
<?php echo $global_import; ?>
body {
  font-family: <?php echo $font_family; ?>;
  font-style: normal;
  font-weight: <?php echo $base_weight; ?>;
<?php echo $inline_import; ?>
}