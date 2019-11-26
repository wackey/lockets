<?php
if (!defined('WP_UNINSTALL_PLUGIN'))
    exit();

/***------------------------------------------
　プラグインアンインストール
------------------------------------------***/
function lockets_delete_plugin() {
    delete_option('rakuten_affiliate_id');
    delete_option('rakuten_search_token');
    delete_option('lockets_rakuten_travel_template');

    delete_option('jalan_webservice_key');
    delete_option('lockets_jalan_template');

    delete_option('valuecommerce_pid');
    delete_option('lockets_valuecommerce_token');
    delete_option('lockets_linkshare_token');
    delete_option('lockets_amzacckey');
    delete_option('lockets_amzseckey');
    delete_option('lockets_amzassid');

    delete_option('recruit_webservice_key');
    delete_option('lockets_hotpepper_template');

    delete_option('gnavi_webservice_key');
    delete_option('lockets_gnavi_template');
    
    delete_option('lockets_gmap_apikey');
    delete_option('lockets_gmap_zoom');
    delete_option('lockets_gmap_width');
    delete_option('lockets_gmap_height');
    delete_option('lockets_googleplace_template');

    delete_option('locketsfeedswitch'); 
    delete_option('lockets_feedurl');
    delete_option('lockets_feedua');
    delete_option('lockets_feedlogourl');
    delete_option('lockets_kanrenfeed');
    delete_option('lockets_classfeed');
}

lockets_delete_plugin();
?>
