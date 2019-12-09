<?php
/***------------------------------------------
　メディアボタン設置
------------------------------------------***/
/* ボタン */
//media_buttons_contextフィルターフック
add_filter( "media_buttons_context", "lockets_media_buttons_context2");


//ボタン追加

function lockets_media_buttons_context2 ( $context ) {

    $context .= <<<EOS
    <a title='Lockets' href='media-upload.php?tab=locketsSearch&type=locketsSearch&TB_iframe=true&width=600&height=550' class='thickbox button'>Lockets</a>
EOS;
    return $context;
}


/* コンテンツ */
// ポップアップウインドウの作成
add_action( 'media_upload_locketsSearch',  'locketsSearch_wp_iframe' );
add_action( "admin_head-media-upload-popup", 'lockets_head');


// 外部配信
$lockets_feedswitch = get_option('lockets_feedswitch');
$lockets_feedurl = "do_feed_".get_option('lockets_feedurl');


if($lockets_feedswitch == "1"){
    function do_feed_lctfmt() {
        $feed_template = WP_PLUGIN_DIR . '/lockets/feeds/lctfmt.php';
        load_template( $feed_template );
    }
    add_action( $lockets_feedurl, 'do_feed_lctfmt');

}