<?php

if ( ! defined( 'WPGENT_VERSION' ) ) define( 'WPGENT_VERSION', '0.0.1' );
if ( ! defined( 'WPGENT_PATH' ) )    define( 'WPGENT_PATH', get_template_directory() . '/' );
if ( ! defined( 'WPGENT_DIR' ) )     define( 'WPGENT_DIR', get_template_directory_uri() . '/' );



function setFont( $current_language, $is_serif = false, $base_weight = 400 ) {
  $current_language = empty( $current_language ) ? get_bloginfo( 'language' ) : $current_language;
  $css_lines = [];
  switch ( $current_language ) {
    case 'ja':
      if ( $is_serif ) {
        $font_families = [
          "Noto Serif CJK JP",
          "Noto Serif Japanese",
          //"游明朝",
          //"YuMincho",
          //"ヒラギノ明朝 ProN W3",
          //"Hiragino Mincho ProN",
          //"HG明朝E",
          //"ＭＳ Ｐ明朝",
          //"ＭＳ 明朝"
        ];
        $font_family = '"'. implode( '","', $font_families ) .'"';
        $css = "<style data-lang=\"$current_language\">\n" . "html,body { font-family: $font_family, sans-serif; font-style: normal; font-weight: $base_weight; }\n" . "</style>\n";
      } else {
        $font_family = 'Noto Sans JP';
        $local_font   = 'Noto Sans CJK JP';
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
          'woff2'      => '//fonts.gstatic.com/ea/notosansjp/v5/NotoSansJP-'. $font_sizes[$base_weight] .'.woff2',
          'woff'        => '//fonts.gstatic.com/ea/notosansjp/v5/NotoSansJP-'. $font_sizes[$base_weight] .'.woff',
          'opentype' => '//fonts.gstatic.com/ea/notosansjp/v5/NotoSansJP-'. $font_sizes[$base_weight] .'otf',
        ];
        $css_lines[] = "@font-face {";
        $css_lines[] = "font-family: \"$font_family\", sans-serif;";
        $css_lines[] = "font-style: normal;";
        $css_lines[] = "font-weight: $base_weight;";
        $css_lines[] = "src: local(\"$local_font\"),";
        foreach ( $webfont_paths as $_k => $_v ) {
          $css_lines[] = "url($_v) format('$_k'),";
        }
        $css = "<style data-lang=\"$current_language\">\n" . substr( implode( "\n", $css_lines ), 0, -1 ) . "\n}\n</style>\n";
      }
      break;
    default:
      if ( $is_serif ) {
        $css_lines[] = '<link href="//fonts.googleapis.com/css?family=Noto+Serif" rel="stylesheet">';
        $css_lines[] = "<style data-lang=\"$current_language\">\n" . "html,body { font-family: 'Noto Serif', sans-serif; font-style: normal; font-weight: $base_weight; }\n" . "</style>\n";
      } else {
        $css_lines[] = '<link href="//fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">';
        $css_lines[] = "<style data-lang=\"$current_language\">\n" . "html,body { font-family: 'Noto Sans', sans-serif; font-style: normal; font-weight: $base_weight; }\n" . "</style>\n";
      }
      $css = implode( "\n", $css_lines );
      break;
  }
  return $css;
}

