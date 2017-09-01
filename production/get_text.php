<?php

function __( $base_text, $textdomain = null, $language = null ) {
    if ( ! isset( $language ) ) {
        if ( ! isset( $_GET['lang'] ) ) {
            $langs = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
            $accept_languages = array_filter( array_map( function( $tag ){
                $tags = explode( ';', $tag );
                $tags = array_map( 'trim', $tags );
                return array_shift( $tags );
            }, $langs ) );
        } else {
            $accept_languages = [ strtolower( $_GET['lang'] ) ];
        }
    } else {
        $accept_languages = [ $language ];
    }

    $_text = [
        "ja" => [
            "ViziXX" => "ViziXX",
            "Main Navigation" => "メイン・ナビゲーション",
            "Apply" => "適用",
            "Cancellation" => "退会",
            "Register Process" => "処理登録",
            "Sensor" => "検知機器",
            "Storing Data" => "データ保存",
            "Split Data" => "データ分割",
            "Storage Time" => "保存期間",
            "Add Widget" => "図表",
            "Table" => "一覧表",
            "Line Graph" => "線グラフ",
            "Bar Graph" => "棒グラフ",
            "Text" => "文字列",
            "Switch" => "切替",
            "Map" => "地図",
            "Table (summary)" => "一覧表（集計）",
            "Line Graph (summary)" => "線グラフ（集計）",
            "Bar Graph (summary)" => "棒グラフ（集計）",
            "Pie Chart (summary)" => "円グラフ（集計）",
            "Register Widget" => "図表の新規作成",
            "Initialize the new widget to be added." => "新たに追加する図表の初期設定を行います。",
            "Widget Title" => "図表の名前",
            "Widget Type" => "図表の種類",
            "Choose Type" => "選択してください",
            "Pie Chart" => "円グラフ",
            "Doughnut Pie Chart" => "ドーナッツ円グラフ",
            "Target Data" => "図表化するデータ",
            "Please fill out this field" => "入力してください",
            "more..." => "以下省略…",
            "Cancel" => "中止する",
            // "" => "",
            
        ],
    ];
    
    if ( array_key_exists( $accept_languages[0], $_text ) && array_key_exists( $base_text, $_text[$accept_languages[0]] ) ) {
        $transrated_text = $_text[$accept_languages[0]][$base_text];
    } else {
        $transrated_text = $base_text;
    }

    return $transrated_text;
}

function _e( $base_text, $textdomain = null, $language = null ) {
    echo __( $base_text, $textdomain, $language );
}
