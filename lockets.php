<?php
/*
Plugin Name: Lockets
Plugin URI: https://lockets.jp/
Description: A plug-in that gets information on spots such as shops and inns from various APIs and displays the latest information embedded in the blog.Also, This plugin will assist you such as creating affiliate links. お店や旅館などスポットに関する情報を各種APIから取得し、ブログ内に最新の情報を埋め込んで表示するプラグイン。また、アフィリエイトリンク作成支援を行います。
Author: wackey
Version: 0.992
Author URI: https://lockets.jp/
License: GPL2
*/
/*  Copyright 2017-2019 wackey (email : takashi.wakimura@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/***------------------------------------------
　各種ライブラリ読み込み
------------------------------------------***/
require_once("lockets_func.php");
require_once("lockets_class.php");


/***------------------------------------------
　管理画面
------------------------------------------***/

// 管理画面サブメニュー用
require_once("lib/admin_rakuten.php");
require_once("lib/admin_hotpepper.php");
require_once("lib/admin_gnavi.php");
require_once("lib/admin_jalan.php");
require_once("lib/admin_affiliate.php");
require_once("lib/admin_feed.php");
require_once("lib/admin_gmap.php");
require_once("lib/admin_main.php");
require_once("lib/mediabutton.php");

// 管理画面メニュー作成関数
function lockets_menu() {
add_menu_page('Lockets', 'Lockets', 8,__FILE__, 'lockets_options','
dashicons-location-alt');
add_submenu_page(__FILE__, '楽天ウェブサービス', '楽天ウェブサービス', 8, "admin_rakuten", 'lockets_rws');
add_submenu_page(__FILE__, 'リクルートWEBサービス', 'リクルートWEBサービス', 8, "admin_recruit_webservice", 'lockets_recruit_webservice');
add_submenu_page(__FILE__, 'ぐるなびWebサービス', 'ぐるなびWebサービス', 8, "admin_gnavi_webservice", 'lockets_gnavi_webservice');
add_submenu_page(__FILE__, 'じゃらんWebサービス', 'じゃらんWebサービス', 8, "admin_jalan_webservice", 'lockets_jalan_webservice');
add_submenu_page(__FILE__, 'Google プレイス', 'Google プレイス', 8, "admin_gmap", 'lockets_gmap');
add_submenu_page(__FILE__, '外部配信β', '外部配信β', 8, "admin_feed", 'lockets_feed');
add_submenu_page(__FILE__, 'その他アフィリエイト', 'その他アフィリエイト', 8, "admin_affiliate", 'lockets_affiliate');
}


/***------------------------------------------
　各種登録
------------------------------------------***/
// ショートコード登録
add_shortcode( 'LocketsRakutenTravel', 'lockets_rakuten_travel_func' );
add_shortcode( 'LocketsJalan', 'lockets_jalan_func' );
add_shortcode( 'LocketsHotpepper', 'lockets_hotpepper_func' );
add_shortcode( 'LocketsGurunavi', 'lockets_gurunavi_func' );
add_shortcode( 'LocketsGMaps', 'lockets_gmaps_func' );
add_shortcode( 'LocketsRakutenItem', 'lockets_rekuten_item_func' );
add_shortcode( 'LocketsValuecommerceItem', 'lockets_valuecommerce_item_func' );
add_shortcode( 'LocketsAmazonItem', 'lockets_amazon_item_func' );

//管理画面登録
add_action('admin_menu', 'lockets_menu');

// ヘッダーに追加
add_action('wp_head','add_vc_automylink');



?>