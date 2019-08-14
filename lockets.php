<?php
/*
Plugin Name: Lockets
Plugin URI: http://lockets.jp/
Description: A plug-in that gets information on spots such as shops and inns from various APIs and displays the latest information embedded in the blog.Also, This plugin will assist you such as creating affiliate links. お店や旅館などスポットに関する情報を各種APIから取得し、ブログ内に最新の情報を埋め込んで表示するプラグイン。また、アフィリエイトリンク作成支援を行います。
Author: wackey
Version: 0.81
Author URI: https://musilog.net/
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
　楽天トラベルホテル情報表示
------------------------------------------***/
// 楽天トラベルホテル単体表示
function lockets_rakuten_travel_func( $atts, $content = null ) {

$rakutentoken= get_option('rakuten_search_token');
$rakutenaffid= get_option('rakuten_affiliate_id');
$lockets_rakuten_travel_template=get_option('lockets_rakuten_travel_template');

// [LocketsRakutenTravel]属性情報取得
extract(shortcode_atts(array(
    'hotelno' => null,
    'zoom' => null,
    'width' => null,
    'height' => null,), $atts));

// リクエストURL
$rwsurl="https://app.rakuten.co.jp/services/api/Travel/HotelDetailSearch/20170426?applicationId=$rakutentoken&affiliateId=$rakutenaffid&format=xml&hotelNo=$hotelno&datumType=1";

// キャッシュ有無確認
$Buff = get_transient( $rwsurl );
if ( $Buff === false ) {
    $options['ssl']['verify_peer']=false;
    $options['ssl']['verify_peer_name']=false;
    if ($Buff = @file_get_contents($rwsurl,false, stream_context_create($options))) {
        $rakutentravelerror = "1";
        set_transient( $rwsurl, $Buff, 3600 * 24 );
    }
}

$xml = simplexml_load_string($Buff);
$hotelBasicInfo = $xml->hotels->hotel->hotelBasicInfo;
$hotelRatingInfo = $xml->hotels->hotel->hotelRatingInfo;

//デフォルトテンプレートの登録
if ($lockets_rakuten_travel_template=="") {
$lockets_rakuten_travel_template= <<<EOT
<p><strong>【施設名称】</strong></p>
<p><a href="【施設情報ページURL】" rel="nofollow"><img src="【施設画像サムネイルURL】"> <img src="【部屋画像サムネイルURL】"></a></p>
<p>【施設特色】<br>
【郵便番号】<br>
【住所１】【住所２】</p>
<p><a href="【宿泊プラン一覧ページURL】" target="_blank" rel="nofollow">宿泊プランはこちら</a></p>
【Google Maps埋め込み】
<p>【楽天ウェブサービスクレジットC】</p>
EOT;
}
$lockets_rakuten_travel_template=str_replace('\\','',$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設番号】',locketsh($hotelBasicInfo->hotelNo),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設名称】',locketsh($hotelBasicInfo->hotelName),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設情報ページURL】',locketsh($hotelBasicInfo->hotelInformationUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【宿泊プラン一覧ページURL】',locketsh($hotelBasicInfo->planListUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【ダイナミックパッケージ宿泊プラン一覧ページUR】',locketsh($hotelBasicInfo->dpPlanListUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【お客様の声ページURL】',locketsh($hotelBasicInfo->reviewUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設かな名称】',locketsh($hotelBasicInfo->hotelKanaName),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設特色】',locketsh($hotelBasicInfo->hotelSpecial),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【最安料金】', number_format(locketsh($hotelBasicInfo->hotelMinCharge)),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【緯度】',locketsh($hotelBasicInfo->latitude),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【経度】',locketsh($hotelBasicInfo->longitude),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【郵便番号】',locketsh($hotelBasicInfo->postalCode),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【住所１】',locketsh($hotelBasicInfo->address1),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【住所２】',locketsh($hotelBasicInfo->address2),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設電話番号】',locketsh($hotelBasicInfo->telephoneNo),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設へのアクセス】',locketsh($hotelBasicInfo->access),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【駐車場情報】',locketsh($hotelBasicInfo->parkingInformation),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【最寄駅名称】',locketsh($hotelBasicInfo->nearestStation),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設画像URL】',locketsh($hotelBasicInfo->hotelImageUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設画像サムネイルURL】',locketsh($hotelBasicInfo->hotelThumbnailUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【部屋画像URL】',locketsh($hotelBasicInfo->roomImageUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【部屋画像サムネイルURL】',locketsh($hotelBasicInfo->roomThumbnailUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設提供地図画像URL】',locketsh($hotelBasicInfo->hotelMapImageUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【投稿件数】',locketsh($hotelBasicInfo->reviewCount),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【★の数（総合）】',locketsh($hotelBasicInfo->reviewAverage),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【お客さまの声（1件目）】',locketsh($hotelBasicInfo->userReview),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【楽天ウェブサービスクレジットA】','<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
<a href="https://webservice.rakuten.co.jp/" target="_blank"><img src="https://webservice.rakuten.co.jp/img/credit/200709/credit_4936.gif" border="0" alt="楽天ウェブサービスセンター" title="楽天ウェブサービスセンター" width="49" height="36"/></a>
<!-- Rakuten Web Services Attribution Snippet TO HERE -->',$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【楽天ウェブサービスクレジットB】','<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
<a href="https://webservice.rakuten.co.jp/" target="_blank"><img src="https://webservice.rakuten.co.jp/img/credit/200709/credit_7052.gif" border="0" alt="楽天ウェブサービスセンター" title="楽天ウェブサービスセンター" width="70" height="52"/></a>
<!-- Rakuten Web Services Attribution Snippet TO HERE -->',$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【楽天ウェブサービスクレジットC】','<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
<a href="https://webservice.rakuten.co.jp/" target="_blank"><img src="https://webservice.rakuten.co.jp/img/credit/200709/credit_22121.gif" border="0" alt="楽天ウェブサービスセンター" title="楽天ウェブサービスセンター" width="221" height="21"/></a>
<!-- Rakuten Web Services Attribution Snippet TO HERE -->',$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【楽天ウェブサービスクレジットD】','<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
<a href="https://webservice.rakuten.co.jp/" target="_blank">Supported by Rakuten Developers</a>
<!-- Rakuten Web Services Attribution Snippet TO HERE -->',$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【楽天ウェブサービスクレジット E】','<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
<a href="https://webservice.rakuten.co.jp/" target="_blank"><img src="https://webservice.rakuten.co.jp/img/credit/200709/credit_31130.gif" border="0" alt="楽天ウェブサービスセンター" title="楽天ウェブサービスセンター" width="311" height="30"/></a>
<!-- Rakuten Web Services Attribution Snippet TO HERE -->',$lockets_rakuten_travel_template);
$gmap = lockets_gmap_draw(locketsh($hotelBasicInfo->hotelName),locketsh($hotelBasicInfo->latitude),locketsh($hotelBasicInfo->longitude),$zoom,$width,$height);
$lockets_rakuten_travel_template=str_replace('【Google Maps埋め込み】',$gmap,$lockets_rakuten_travel_template);
//メモ　その他の要素後日追加

// Lockets feedへの緯度経度連携
$lockets_rakuten_travel_template .= "<!--";
$lockets_rakuten_travel_template .= "<georss:point>".locketsh($hotelBasicInfo->latitude)." ".locketsh($hotelBasicInfo->longitude)."</georss:point>";
$lockets_rakuten_travel_template .= "<georss:featuretypetag>rakuten_travel</georss:featuretypetag>";
$lockets_rakuten_travel_template .= "<georss:relationshiptag>".locketsh($hotelBasicInfo->hotelNo)."</georss:relationshiptag>";
$lockets_rakuten_travel_template .= "<georss:featurename>".locketsh($hotelBasicInfo->hotelName)."</georss:featurename>";
$lockets_rakuten_travel_template .= "LocketsFeedend-->";
    
return $lockets_rakuten_travel_template;

}


/***------------------------------------------
　じゃらんホテル情報表示
------------------------------------------***/
// じゃらんホテル単体表示
function lockets_jalan_func( $atts, $content = null ) {

$jalan_webservice_key= get_option('jalan_webservice_key');
$lockets_jalan_template= get_option('lockets_jalan_template');

// [LocketsJalan]属性情報取得
extract(shortcode_atts(array(
    'hotelno' => null,
    'zoom' => null,
    'width' => null,
    'height' => null,), $atts));

// リクエストURL
$jalanurl="http://jws.jalan.net/APIAdvance/HotelSearch/V1/?key=$jalan_webservice_key&h_id=$hotelno";

// キャッシュ有無確認
$Buff = get_transient($jalanurl);
if ( $Buff === false ) {
    $Buff = file_get_contents($jalanurl);
    set_transient($jalanurl, $Buff, 3600 * 24 );
}

$xml = @simplexml_load_string($Buff);//warning防止
$jalanhotel = $xml->Hotel;

//デフォルトテンプレートの登録
if ($lockets_jalan_template=="") {
$lockets_jalan_template= <<<EOT
<p><strong>【宿名漢字】</strong></p>
<p><a href="【宿詳細ページURL】" rel="nofollow"><img src="【宿画像URL】"></a></p>
<p>【宿画像キャプション】<br>
【郵便番号】<br>
【住所】</p>
【Google Maps埋め込み】
<p>【じゃらんクレジットA】</p>
EOT;
}
$lockets_jalan_template=str_replace('\\','',$lockets_jalan_template);
$lockets_jalan_template=str_replace('【宿番号】',locketsh($jalanhotel->HotelID),$lockets_jalan_template);
$lockets_jalan_template=str_replace('【宿名漢字】',locketsh($jalanhotel->HotelName),$lockets_jalan_template);
$lockets_jalan_template=str_replace('【郵便番号】',locketsh($jalanhotel->PostCode	),$lockets_jalan_template);
$lockets_jalan_template=str_replace('【住所】',locketsh($jalanhotel->HotelAddress	),$lockets_jalan_template);
    
$lockets_jalan_template=str_replace('【宿詳細ページURL】',locketsh($jalanhotel->HotelDetailURL),$lockets_jalan_template);

$lockets_jalan_template=str_replace('【キャッチ】',locketsh($jalanhotel->HotelCatchCopy),$lockets_jalan_template);
$lockets_jalan_template=str_replace('【コピー】',locketsh($jalanhotel->HotelCaption),$lockets_jalan_template);

$lockets_jakan_yadogazo=str_replace ("http://","https://",locketsh($jalanhotel->PictureURL));
$lockets_jalan_template=str_replace('【宿画像URL】',$lockets_jakan_yadogazo,$lockets_jalan_template);
$lockets_jalan_template=str_replace('【宿画像キャプション】',locketsh($jalanhotel->PictureCaption),$lockets_jalan_template);

$lockets_jalan_template=str_replace('【参考料金】',locketsh($jalanhotel->SampleRateFrom),$lockets_jalan_template);


$lockets_jalan_template=str_replace('【じゃらんクレジットA】','<a href="http://www.jalan.net/jw/jwp0000/jww0001.do"><img src="https://www.jalan.net/jalan/doc/jws/images/jws_88_50_blue.gif" alt="じゃらん Web サービス" title="じゃらん Web サービス" border="0"></a>',$lockets_jalan_template);
$lockets_jalan_template=str_replace('【じゃらんクレジットB】','<a href="http://www.jalan.net/jw/jwp0000/jww0001.do"><img src="https://www.jalan.net/jalan/doc/jws/images/jws_88_50_gray.gif" alt="じゃらん Web サービス" title="じゃらん Web サービス" border="0"></a>',$lockets_jalan_template);
$lockets_jalan_template=str_replace('【じゃらんクレジットC】','<a href="http://www.jalan.net/jw/jwp0000/jww0001.do">じゃらん Web サービス</a>',$lockets_jalan_template);

//　日本測地系（ミリ秒）から世界測地系へ変換
$jalanlng = locketsh($jalanhotel->X);
$jalanlat = locketsh($jalanhotel->Y);
$lat = $jalanlat - $jalanlat * 0.00010695 + $jalanlng * 0.000017464 + 0.0046017;
$lon = $jalanlng - $jalanlat * 0.000046038 - $jalanlng * 0.000083043 + 0.010040;

$gmap = lockets_gmap_draw(locketsh($jalanhotel->HotelName),$lat,$lon,$zoom,$width,$height);
$lockets_jalan_template=str_replace('【Google Maps埋め込み】',$gmap,$lockets_jalan_template);
//メモ　その他の要素後日追加

// Lockets feedへの緯度経度連携
$lockets_jalan_template .= "<!--";
$lockets_jalan_template .= "<georss:point>".$lat." ".$lon."</georss:point>";
$lockets_jalan_template .= "<georss:featuretypetag>jalan</georss:featuretypetag>";
$lockets_jalan_template .= "<georss:relationshiptag>".locketsh($jalanhotel->HotelID)."</georss:relationshiptag>";
$lockets_jalan_template .= "<georss:featurename>".locketsh($jalanhotel->HotelName)."</georss:featurename>";
$lockets_jalan_template .= "LocketsFeedend-->";

return $lockets_jalan_template;

}


/***------------------------------------------
　ホットペッパー店舗情報表示
------------------------------------------***/
// ホットペッパー店舗情報単体表示
function lockets_hotpepper_func( $atts, $content = null ) {

$recruit_webservice_key= get_option('recruit_webservice_key');
$lockets_hotpepper_template= get_option('lockets_hotpepper_template');

// [LocketsHotpepper]属性情報取得
extract(shortcode_atts(array(
    'shopid' => null,
    'zoom' => null,
    'width' => null,
    'height' => null,), $atts));

// リクエストURL
$recruiturl="http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key=$recruit_webservice_key&id=$shopid&datum=world";
//echo $recruiturl;//■テスト用

// キャッシュ有無確認
$Buff = get_transient( $recruiturl );
if ( $Buff === false ) {
    $Buff = file_get_contents($recruiturl);
    set_transient( $shopid, $Buff, 3600 * 24 );
}

$xml = simplexml_load_string($Buff);
$shop = $xml->shop;

//デフォルトテンプレートの登録
if ($lockets_hotpepper_template=="") {
$lockets_hotpepper_template= <<<EOT
<p><strong><a href="【店舗URL(PC)】">【掲載店名】</a></strong><p>
<p>【お店キャッチ】</p>
<p><img src="【写真PC向けLサイズ】 " class="img-responsive"></p>
<p>住所：【住所】</p>
<p>交通アクセス：【交通アクセス】</p>
<p>営業時間：【営業時間】</p>
<p>定休日：【定休日】</p>

【Google Maps埋め込み】
<p>【HOT PEPPERクレジットA】</p>
EOT;
}

$lockets_hotpepper_template=str_replace('\\','',$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【お店ID】',locketsh($shop->id),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【掲載店名】',locketsh($shop->name),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【ロゴ画像URL】',locketsh($shop->logo_image),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【掲載店名かな】',locketsh($shop->name_kana),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【住所】',locketsh($shop->address),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【最寄駅名】',locketsh($shop->station_name),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【緯度】',locketsh($shop->lat),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【経度】',locketsh($shop->lng),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【料金備考】',locketsh($shop->budget_memo),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【お店キャッチ】',locketsh($shop->catch),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【総席数】',locketsh($shop->capacity),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【交通アクセス】',locketsh($shop->access),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【店舗URL(PC)】',locketsh($shop->urls->pc),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【写真PC向けLサイズ】',locketsh($shop->photo->pc->l),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【写真PC向けMサイズ】',locketsh($shop->photo->pc->m),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【写真PC向けSサイズ】',locketsh($shop->photo->pc->s),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【営業時間】',locketsh($shop->open),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【定休日】',locketsh($shop->close),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【最大宴会収容人数】',locketsh($shop->party_capacity),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【WiFi 有無】',locketsh($shop->wifi),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【ウェディング･二次会】',locketsh($shop->wedding),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【コース】',locketsh($shop->course),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【飲み放題】',locketsh($shop->free_drink),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【食べ放題】',locketsh($shop->free_food),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【個室】',locketsh($shop->private_room),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【掘りごたつ】',locketsh($shop->horigotatsu),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【座敷】',locketsh($shop->tatami),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【カード可】',locketsh($shop->card),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【禁煙席】',locketsh($shop->non_smoking),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【貸切可】',locketsh($shop->charter),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【携帯電話OK】',locketsh($shop->ktai),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【駐車場】',locketsh($shop->parking),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【バリアフリー】',locketsh($shop->barrier_free),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【その他設備】',locketsh($shop->other_memo),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【ソムリエ】',locketsh($shop->sommelier),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【オープンエア】',locketsh($shop->open_air),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【ライブ・ショー】',locketsh($shop->show),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【エンタメ設備】',locketsh($shop->equipment),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【カラオケ】',locketsh($shop->karaoke),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【バンド演奏可】',locketsh($shop->band),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【TV・プロジェクター】',locketsh($shop->tv),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【英語メニュー】',locketsh($shop->english),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【ペット可】',locketsh($shop->pet),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【お子様連れ】',locketsh($shop->child),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【ランチ】',locketsh($shop->lunch),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【23時以降も営業】',locketsh($shop->midnight),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【備考】',locketsh($shop->shop_detail_memo),$lockets_hotpepper_template);
//$lockets_hotpepper_template=str_replace('【クーポンURL(PC)】',locketsh($shop->coupon_urls->pc),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【クーポンURL(PC)】',locketsh($shop->urls->pc),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【HOT PEPPERクレジットA】','<a href="http://webservice.recruit.co.jp/"><img src="https://webservice.recruit.co.jp/banner/hotpepper-s.gif" alt="ホットペッパー Webサービス" width="135" height="17" border="0" title="ホットペッパー Webサービス"></a>',$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【HOT PEPPERクレジットB】','<a href="http://webservice.recruit.co.jp/"><img src="https://webservice.recruit.co.jp/banner/hotpepper-m.gif" alt="ホットペッパー Webサービス" width="88" height="35" border="0" title="ホットペッパー Webサービス"></a>',$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【HOT PEPPERクレジットC】','Powered by <a href="http://webservice.recruit.co.jp/">ホットペッパー Webサービス</a>',$lockets_hotpepper_template);
$gmap = lockets_gmap_draw(locketsh($shop->name),locketsh($shop->lat),locketsh($shop->lng),$zoom,$width,$height);
$lockets_hotpepper_template=str_replace('【Google Maps埋め込み】',$gmap,$lockets_hotpepper_template);
//抜けている項目は後日追加する

// Lockets feedへの緯度経度連携
$lockets_hotpepper_template .= "<!--";
$lockets_hotpepper_template .= "<georss:point>".locketsh($shop->lat)." ".locketsh($shop->lng)."</georss:point>";
$lockets_hotpepper_template .= "<georss:featuretypetag>hotpepper</georss:featuretypetag>";
$lockets_hotpepper_template .= "<georss:relationshiptag>".locketsh($shop->id)."</georss:relationshiptag>";
$lockets_hotpepper_template .= "<georss:featurename>".locketsh($shop->name)."</georss:featurename>";
$lockets_hotpepper_template .= "LocketsFeedend-->";

return $lockets_hotpepper_template;
}

/***------------------------------------------
　ぐるなび店舗情報表示
------------------------------------------***/
// ぐるなび店舗情報単体表示
function lockets_gurunavi_func( $atts, $content = null ) {

$gnavi_webservice_key= get_option('gnavi_webservice_key');
$lockets_gnavi_template= get_option('lockets_gnavi_template');

// [LocketsGurunavi]属性情報取得
extract(shortcode_atts(array(
    'shopid' => null,
    'zoom' => null,
    'width' => null,
    'height' => null,), $atts));

// リクエストURL
$gurunaviurl="https://api.gnavi.co.jp/RestSearchAPI/v3/?keyid=$gnavi_webservice_key&id=$shopid&coordinates_mode=2";

// キャッシュ有無確認
$Buff = get_transient( $shopid );
    if ( $Buff === false ) {
    $Buff = file_get_contents($gurunaviurl);
    set_transient( $shopid, $Buff, 3600 * 24 );
}

$json = json_decode($Buff);
$shop = $json->rest[0];

//デフォルトテンプレートの登録
if ($lockets_gnavi_template=="") {
$lockets_gnavi_template= <<<EOT
<p><strong><a href="【店舗URL(PC)】">【掲載店名】</a></strong><p>
<p>【PR文（短:最大50文字）】</p>
<p><img src="【店舗画像1のURL】" class="img-responsive"></p>
<p>住所：【住所】</p>
<p>交通アクセス：【最寄駅名】</p>
<p>営業時間：【営業時間】</p>
<p>定休日：【休業日】</p>

【Google Maps埋め込み】
<p>【ぐるなびクレジットA】</p>
EOT;
}

$lockets_gnavi_template=str_replace('\\','',$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【お店ID】',locketsh($shop->id),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【情報更新日時】',locketsh($shop->update_date),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【掲載店名】',locketsh($shop->name),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【掲載店名かな】',locketsh($shop->name_kana),$lockets_gnavi_template);

$lockets_gnavi_template=str_replace('【緯度】',locketsh($shop->latitude),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【経度】',locketsh($shop->longitude),$lockets_gnavi_template);

$lockets_gnavi_template=str_replace('【フリーワードカテゴリー】',locketsh($shop->category),$lockets_gnavi_template);

$lockets_gnavi_template=str_replace('【店舗URL(PC)】',locketsh($shop->url),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【クーポンURL(PC)】',locketsh($shop->coupon_url->pc),$lockets_gnavi_template);

$lockets_gnavi_template=str_replace('【店舗画像1のURL】',locketsh($shop->image_url->shop_image1),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【店舗画像2のURL】',locketsh($shop->image_url->shop_image2),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【QRコード】',locketsh($shop->image_url->qrcode),$lockets_gnavi_template);


$lockets_gnavi_template=str_replace('【住所】',locketsh($shop->address),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【電話番号】',locketsh($shop->tel),$lockets_gnavi_template);

$lockets_gnavi_template=str_replace('【営業時間】',locketsh($shop->opentime),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【休業日】',locketsh($shop->holiday),$lockets_gnavi_template);

$lockets_gnavi_template=str_replace('【最寄駅名】',locketsh($shop->access->line).locketsh($shop->access->station),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【交通アクセス】',locketsh($shop->access->line).locketsh($shop->access->station).locketsh($shop->access->station_exit)."徒歩".locketsh($shop->access->walk)."分<br>".locketsh($shop->access->note),$lockets_gnavi_template);

$lockets_gnavi_template=str_replace('【平均予算】',locketsh($shop->budget),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【宴会・パーティ平均予算】',locketsh($shop->party),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【ランチタイム平均予算】',locketsh($shop->lunch),$lockets_gnavi_template);


$lockets_gnavi_template=str_replace('【PR文（短:最大50文字）】',locketsh($shop->pr->pr_short),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【PR文（長:最大200文字）】',locketsh($shop->pr->pr_long),$lockets_gnavi_template);


$lockets_gnavi_template=str_replace('【ぐるなびクレジットA】','<a href="http://api.gnavi.co.jp/api/scope/" target="_blank"><img src="https://api.gnavi.co.jp/api/img/credit/api_155_20.gif" width="155" height="20" border="0" alt="グルメ情報検索サイト　ぐるなび"></a>',$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【ぐるなびクレジットB】','<a href="http://api.gnavi.co.jp/api/scope/" target="_blank"><img src="https://api.gnavi.co.jp/api/img/credit/api_90_35.gif" width="90" height="35" border="0" alt="グルメ情報検索サイト　ぐるなび"></a>',$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【ぐるなびクレジットC】','Supported by <a href="http://api.gnavi.co.jp/api/scope/" target="_blank">ぐるなびWebService</a>',$lockets_gnavi_template);
$gmap = lockets_gmap_draw(locketsh($shop->name),locketsh($shop->latitude),locketsh($shop->longitude),$zoom,$width,$height);
$lockets_gnavi_template=str_replace('【Google Maps埋め込み】',$gmap,$lockets_gnavi_template);
//抜けている項目は後日追加する
    
// Lockets feedへの緯度経度連携
$lockets_gnavi_template .= "<!--";
$lockets_gnavi_template .= "<georss:point>".locketsh($shop->latitude)." ".locketsh($shop->longitude)."</georss:point>";
$lockets_gnavi_template .= "<georss:featuretypetag>gurunavi</georss:featuretypetag>";
$lockets_gnavi_template .= "<georss:relationshiptag>".locketsh($shop->id)."</georss:relationshiptag>";
$lockets_gnavi_template .= "<georss:featurename>".locketsh($shop->name)."</georss:featurename>";
$lockets_gnavi_template .= "LocketsFeedend-->";

return $lockets_gnavi_template;
}

                                    
/***------------------------------------------
　Google プレイス API ＆　Google Maps表示機能
------------------------------------------***/
// ショートコード呼び出し
function lockets_gmaps_func ( $atts, $content = null ) {

    // [LocketsGMaps]属性情報取得
    extract(shortcode_atts(array(
        'keyword' => null,
        'lat' => null,
        'lng' => null,
        'zoom' => null,
        'width' => null,
        'height' => null,
        'placeid' => null,), $atts));

    if (!$placeid == null) {
    //プレイスAPIを使う処理
        $lockets_gmap_apikey= get_option('lockets_gmap_apikey');
        $lockets_googleplace_template= get_option('lockets_googleplace_template');
        $gmapurl="https://maps.googleapis.com/maps/api/place/details/xml?key=$lockets_gmap_apikey&placeid=$placeid&fields=name,rating,formatted_address,formatted_phone_number,geometry,website&language=ja";
        $Buff = file_get_contents($gmapurl);//キャッシュ使用しない
        $xml = simplexml_load_string($Buff);
        $gmapplaces = $xml->result;
        $keyword = locketsh($gmapplaces->name);
        $lat = locketsh($gmapplaces->geometry->location->lat);
        $lng = locketsh($gmapplaces->geometry->location->lng);

        //デフォルトテンプレートの登録
        if ($lockets_googleplace_template=="") {
        $lockets_googleplace_template =  <<<EOT
【Google Maps埋め込み】
EOT;
}
    $lockets_googleplace_template=str_replace('【スポット名】',$keyword,$lockets_googleplace_template);
    $gmap = lockets_gmap_draw($keyword,$lat,$lng,$zoom,$width,$height);
    $lockets_googleplace_template=str_replace('【Google Maps埋め込み】',$gmap,$lockets_googleplace_template);
    $lockets_googleplace_template=str_replace('【住所】',locketsh($gmapplaces->formatted_address),$lockets_googleplace_template);
    $lockets_googleplace_template=str_replace('【電話番号】',locketsh($gmapplaces->formatted_phone_number),$lockets_googleplace_template);
    if ($gmapplaces->website) {
    $textlink = '<a href="'.locketsh($gmapplaces->website).'" target="_blank">'.$keyword.'</a>';

    $lockets_googleplace_template=str_replace('【Webサイトテキストリンク】',$textlink,$lockets_googleplace_template);
    } else {
        $lockets_googleplace_template=str_replace('【Webサイトテキストリンク】',"-",$lockets_googleplace_template);
    }
    $lockets_googleplace_template=str_replace('【GoogleクレジットA】','<img src="'.WP_PLUGIN_URL.'/lockets/images/powered_by_google_on_white.png">',$lockets_googleplace_template);
    $lockets_googleplace_template=str_replace('【GoogleクレジットB】','<img src="'.WP_PLUGIN_URL.'/lockets/images/powered_by_google_on_non_white.png">',$lockets_googleplace_template);
    // Lockets feedへの緯度経度連携
    $lockets_googleplace_template .= "<!--";
    $lockets_googleplace_template .= "<georss:point>".$lat." ".$lng ."</georss:point>";
    $lockets_googleplace_template .= "<georss:featuretypetag>google_place</georss:featuretypetag>";
    $lockets_googleplace_template .= "<georss:relationshiptag>".$placeid."</georss:relationshiptag>";
    $lockets_googleplace_template .= "<georss:featurename>".$keyword."</georss:featurename>";
    $lockets_googleplace_template .= "LocketsFeedend-->";
    $ret= $lockets_googleplace_template;
} else {
    $ret= lockets_gmap_draw($keyword,$lat,$lng,$zoom,$width,$height);
}
    return $ret;
}



/***------------------------------------------
　楽天商品検索結果表示（タグID検索は追って実装）
------------------------------------------***/
function lockets_rekuten_item_func( $atts, $content = null ) {

$rakutentoken= get_option('rakuten_search_token');
$rakutenaffid= get_option('rakuten_affiliate_id');
//$lockets_rakuten_travel_template=get_option('lockets_rakuten_item_template');

// [LocketsRakutenItem]属性情報取得
extract(shortcode_atts(array(
    'itemcode' => null,
    'tagid' => null,), $atts));

// 商品ID検索（タグID検索は追って実装）
$rwsurl="https://app.rakuten.co.jp/services/api/IchibaItem/Search/20170706?applicationId=$rakutentoken&affiliateId=$rakutenaffid&itemCode=$itemcode&tagid=$tagid&format=xml";

// キャッシュ有無確認
$Buff = get_transient( $rwsurl );
if ( $Buff === false ) {
    $options['ssl']['verify_peer']=false;
    $options['ssl']['verify_peer_name']=false;
    if ($Buff = @file_get_contents($rwsurl,false, stream_context_create($options))) {
        $rakutentravelerror = "1";
        set_transient( $rwsurl, $Buff, 3600 * 24 );
    }
}

$xml = simplexml_load_string($Buff);
$resultcount = $xml->count;
$items = $xml->Items->Item;
    
//デフォルトテンプレートの登録
if ($lockets_rakuten_item_template=="") {
$lockets_rakuten_item_template= <<<EOT
<p><a href="【商品URL】" rel="nofollow" target="_blank"><img src="【商品画像128x128URL】"></a></p>
<p><a href="【商品URL】" rel="nofollow" target="_blank"><strong>【商品名】</strong></a><br>
（<a href="【店舗URL】" rel="nofollow" target="_blank">【店舗名】</a>）</p>

<p>【楽天ウェブサービスクレジットA】</p>
EOT;
}

    $contents="";
if ($items) {
    foreach ($items as $item) {
        $lockets_rakuten_item_template=str_replace('【商品名】',locketsh($item->itemName),$lockets_rakuten_item_template);
        $lockets_rakuten_item_template=str_replace('【商品URL】',locketsh($item->itemUrl),$lockets_rakuten_item_template);
        //$pictures128 = $item->mediumImageUrls->imageUrl;
        $lockets_rakuten_item_template=str_replace('【商品画像128x128URL】',locketsh($item->mediumImageUrls->imageUrl[0]),$lockets_rakuten_item_template);
        $lockets_rakuten_item_template=str_replace('【店舗URL】',locketsh($item->shopUrl),$lockets_rakuten_item_template);
        $lockets_rakuten_item_template=str_replace('【店舗名】',locketsh($item->shopName),$lockets_rakuten_item_template);
        $lockets_rakuten_item_template=str_replace('【楽天ウェブサービスクレジットA】','<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
        <a href="https://webservice.rakuten.co.jp/" target="_blank"><img src="https://webservice.rakuten.co.jp/img/credit/200709/credit_4936.gif" border="0" alt="楽天ウェブサービスセンター" title="楽天ウェブサービスセンター" width="49" height="36"/></a>
        <!-- Rakuten Web Services Attribution Snippet TO HERE -->',$lockets_rakuten_item_template);
        $lockets_rakuten_item_template=str_replace('【楽天ウェブサービスクレジットB】','<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
        <a href="https://webservice.rakuten.co.jp/" target="_blank"><img src="https://webservice.rakuten.co.jp/img/credit/200709/credit_7052.gif" border="0" alt="楽天ウェブサービスセンター" title="楽天ウェブサービスセンター" width="70" height="52"/></a>
        <!-- Rakuten Web Services Attribution Snippet TO HERE -->',$lockets_rakuten_item_template);
        $lockets_rakuten_item_template=str_replace('【楽天ウェブサービスクレジットC】','<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
        <a href="https://webservice.rakuten.co.jp/" target="_blank"><img src="https://webservice.rakuten.co.jp/img/credit/200709/credit_22121.gif" border="0" alt="楽天ウェブサービスセンター" title="楽天ウェブサービスセンター" width="221" height="21"/></a>
        <!-- Rakuten Web Services Attribution Snippet TO HERE -->',$lockets_rakuten_item_template);
        $lockets_rakuten_item_template=str_replace('【楽天ウェブサービスクレジットD】','<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
        <a href="https://webservice.rakuten.co.jp/" target="_blank">Supported by 楽天ウェブサービス</a>
        <!-- Rakuten Web Services Attribution Snippet TO HERE -->',$lockets_rakuten_item_template);
        $contents.=$lockets_rakuten_item_template;
    }
    return $contents;
}
}

/***------------------------------------------
　バリューコマース商品検索結果表示（JANコード検索は追って実装）
------------------------------------------***/
function lockets_valuecommerce_item_func( $atts, $content = null ) {

        $lockets_valuecommerce_token = get_option('lockets_valuecommerce_token');

// [LocketsRakutenItem]属性情報取得
extract(shortcode_atts(array(
    'itemcode' => null,
    'jancode' => null,), $atts));

        $vcurl = "http://webservice.valuecommerce.ne.jp/productdb/search?token=$lockets_valuecommerce_token&product_id=$itemcode";
//echo $vcurl;
        $Buff = get_transient( $vcurl );
        if ( $Buff === false ) {
            $options['ssl']['verify_peer']=false;
            $options['ssl']['verify_peer_name']=false;
            if ($Buff = @file_get_contents($vcurl,false, stream_context_create($options))) {
                $rakutenitemerror = "1";
                set_transient( $vcurl, $Buff, 3600 * 24 );
            }
        }

        $Buff = str_replace('vc:', 'vc', $Buff);
        $Buff = str_replace('&', '&amp;', $Buff);
        $xml = simplexml_load_string($Buff);
        $items = $xml->channel->item;
        $resultcount = locketsh($xml->channel->vcresultcount);
        $totalpage = locketsh($xml->channel->vcpagecount);

//デフォルトテンプレートの登録
if ($lockets_valuecommerce_item_template=="") {
$lockets_valuecommerce_item_template= <<<EOT
<p><a href="【商品URL】" rel="nofollow" target="_blank"><img src="【商品画像】"></a></p>
<p><a href="【商品URL】" rel="nofollow" target="_blank"><strong>【商品名】</strong></a><br>
（【店舗名】）</p>

EOT;
}

    $contents="";
        if ($items) {
            foreach ($items as $item) {
        $lockets_valuecommerce_item_template=str_replace('【商品名】',locketsh($item->title),$lockets_valuecommerce_item_template);
            // 画像URL取り出し
                
            $img = array();
            foreach($item->vcimage as $vcimg) {
                $img[]=$vcimg["url"];
            }

            if (strlen($img[1])) {
                $imgurl = locketsh($img[1]);
            } else {
                if (strlen($img[2])) {
                    $imgurl = locketsh($img[2]);
                } else {
                $imgurl = WP_PLUGIN_URL."/vc_search/c_img/noimage.gif";
                }
            }
            
        $lockets_valuecommerce_item_template=str_replace('【商品URL】',locketsh($item->link),$lockets_valuecommerce_item_template);
        //$pictures128 = $item->mediumImageUrls->imageUrl;
        $lockets_valuecommerce_item_template=str_replace('【商品画像】',locketsh( $imgurl),$lockets_valuecommerce_item_template);
        $lockets_valuecommerce_item_template=str_replace('【店舗名】',locketsh($item->vcmerchantName ),$lockets_valuecommerce_item_template);
                $contents.=$lockets_valuecommerce_item_template;
            }
       return $contents;
}
}


/***------------------------------------------
　Amazon商品検索結果表示（JANコード検索は追って実装）
------------------------------------------***/
function lockets_amazon_item_func( $atts, $content = null ) {

// [LocketsAmazonItem]属性情報取得
extract(shortcode_atts(array(
    'asin' => null,
    'jancode' => null,), $atts));

        $cacheid = "amazon".$asin;
        $Buff = get_transient($cacheid);

        if ( $Buff === false ) {
            $lockets_Amazonitemsearchtapi= new lockets_Amazonitemsearchtapi();
            $awsurl = $lockets_Amazonitemsearchtapi->awsasinrequesturl($asin);

            if ($Buff = file_get_contents($awsurl,false, stream_context_create($options))) {
                $rakutenitemerror = "1";
                set_transient( $cacheid, $Buff, 3600 * 24 );
            }
        }

        $xml = simplexml_load_string($Buff);
        $items = $xml->Items->Item;
        $resultcount = locketsh($xml->Items->TotalResults);
        $totalpage = locketsh($xml->Items->TotalPages);

//デフォルトテンプレートの登録
if ($lockets_amazon_item_template=="") {
$lockets_amazon_item_template= <<<EOT
<p><a href="【商品URL】" rel="nofollow" target="_blank"><img src="【商品画像】"></a><br>
<a href="【商品URL】" rel="nofollow" target="_blank"><strong>【商品名】</strong></a></p>

EOT;
}

    $contents="";
        if ($items) {
            foreach ($items as $item) {
                $lockets_amazon_item_template=str_replace('【商品名】',locketsh($item->ItemAttributes->Title),$lockets_amazon_item_template);
                $lockets_amazon_item_template=str_replace('【商品URL】',locketsh($item->DetailPageURL),$lockets_amazon_item_template);
                //$pictures128 = $item->mediumImageUrls->imageUrl;
                $lockets_amazon_item_template=str_replace('【商品画像】',locketsh($item->MediumImage->URL),$lockets_amazon_item_template);
                $contents.=$lockets_amazon_item_template;
            }
       return $contents;
}
}


/***------------------------------------------
　管理画面
------------------------------------------***/

// 管理画面サブメニュー用
require_once("admin_rakuten.php");
require_once("admin_hotpepper.php");
require_once("admin_gnavi.php");
require_once("admin_jalan.php");
require_once("admin_affiliate.php");
require_once("admin_feed.php");
require_once("admin_gmap.php");



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

// 管理画面描画
function lockets_options() {
    $rakutentoken= get_option('rakuten_search_token');
    $rakutenaffid= get_option('rakuten_affiliate_id');
    $jalan_webservice_key= get_option('jalan_webservice_key');
    $recruit_webservice_key= get_option('recruit_webservice_key');
    $gnavi_webservice_key= get_option('gnavi_webservice_key');
    $valuecommerce_pid= get_option('valuecommerce_pid');
    $lockets_amzacckey=get_option('lockets_amzacckey');
    $lockets_amzseckey=get_option('lockets_amzseckey');
    $lockets_amzassid=get_option('lockets_amzassid');
    $lockets_gmap_apikey= get_option('lockets_gmap_apikey');
    $locketsfeedswitch= get_option('locketsfeedswitch');
?>

<div class="wrap">

<h2>Lockets設定画面</h2>

<h2>使い方</h2>
    <p>投稿画面で“Lockets”ボタンを押して、表示させたいコンテンツをキーワード検索し“挿入”ボタンを押すことで記事中にそのコンテンツを表示するショートコードを挿入します。</p>

<p>ショートコードとは次のような形で、ブログ記事中にその情報を呼び出して表示する印のようなものです。<br>
    <strong>[LocketsRakutenTravel hotelno="xxxxxxx"]</strong></p>

<p>ブログ記事を表示する度に最新の情報をそれぞれのサービスから取得し、最新の情報を表示します。<br>
    つまり、それぞれのサービス上で価格や営業時間などの情報が更新されればその最新の情報を元にコンテンツを表示します。</p>

    <p>また、表示するテンプレートはHTMLで自由にカスタマイズすることが可能です。そのカスタマイズで表示させたい項目を自由にアレンジ出来ます</p>

<h2>埋め込める情報一覧</h2>

<h3>旅行・ホテル情報系</h3>
<ul>
<li><?php
if ($rakutentoken=="" and $rakutenaffid=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "楽天トラベル（楽天アフィリエイト使用）"
?> </li>
<li><?php
if ($jalan_webservice_key=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "じゃらん　※LinkSwitchでバリューコマースアフィリエイト使用可"
?> </li> 
</ul>

<h3>飲食店情報系</h3>
<ul>
<li><?php
if ($recruit_webservice_key=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "HOT PEPPER　※LinkSwitchでバリューコマースアフィリエイト使用可"
?> </li> 
<li><?php
if ($gnavi_webservice_key=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "ぐるなび"
?> </li> 
</ul>

<h3>商品情報系</h3>
<ul>
<li><?php
if ($rakutentoken=="" and $rakutenaffid=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "楽天市場（楽天アフィリエイト）"
?> </li>
<li><?php
if ($lockets_amzacckey=="" and $lockets_amzseckey=="" and $lockets_amzassid=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "Amazonアソシエイト"
?> </li>
</ul>

<h3>その他スポット</h3>
<ul>
<li><?php
if ($lockets_gmap_apikey=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "Google プレイス"
?> </li>
</ul>

<p>参考：サーバーIPアドレス：<?php $url = plugins_url('lib/remoteaddr.php',__FILE__);
$options['ssl']['verify_peer']=false;
$options['ssl']['verify_peer_name']=false;
echo file_get_contents($url,false, stream_context_create($options)); ?></p>
    
<h3>その他アフィリエイト機能</h3>
<ul>
<li><?php
if ($valuecommerce_pid=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "バリューコマース LinkSwitch<br>LinkSwitchに必要なJavaScriptを自動的に挿入します。<br>HOTPEPPERやじゃらんなど対応ECサイトと提携していると上記リンクが自動的にバリューマースのアフィリエイトリンクに置き換わります。"
?> </li>

    
</ul>
<p>※[設定済][未設定]はAPIキーの入力のみのチェックです。HTMLテンプレートは編集しなくても動作します。<br>
    Google Maps表示は各スポット検索と連携して使用出来ますが、Googleプレイスは設定が必要です。</p>

</div>

<?php
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


// アンインストール
register_deactivation_hook(__FILE__, 'remove_lockets');


/***------------------------------------------
　プラグインアンインストール
------------------------------------------***/
function remove_lockets()
{
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

    delete_option('locketsfeedswitch');  
}

/***------------------------------------------
　LinkSwitch追加
------------------------------------------***/
function add_vc_automylink() {
    $vc_pid= get_option('valuecommerce_pid');
    if ($vc_pid!=="") {
        echo '<script type="text/javascript" language="javascript">';
        echo '    var vc_pid = "'.locketsh(stripslashes($vc_pid)).'";';
        echo '</script><script type="text/javascript" src="//aml.valuecommerce.com/vcdal.js" async></script>';
    }
}



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

function locketsSearch_wp_iframe() {
        wp_iframe( 'media_upload_lockets2_form' );
}

// 検索インタフェース用
function media_upload_lockets2_form() {
	add_filter( "media_upload_tabs", "lockets_upload_tabs" ,1000);
	media_upload_header();

$searchword =sanitize_text_field($_GET['searchword']);
$useapi = sanitize_text_field($_GET['usuapi']);

echo <<< EOS
<div id="test">
    <form action="media-upload.php" method="get">
        <h2>Lockets Search</h2>
        <p>お店やホテルやスポット、商品などを検索して挿入ボタンを押すと記事中にその情報表示用ショートコードを挿入します。</p>

        検索キーワード：<input type="text" name="searchword" value="$searchword" />
        <h4>検索対象</h4>
EOS;
switch ($useapi) {
        case '':
        case 'ホットペッパー':
        echo <<< EOS
        <input type="radio" name="usuapi" value="ホットペッパー" checked> ホットペッパー　
        <input type="radio" name="usuapi" value="ぐるなび"> ぐるなび　
        <input type="radio" name="usuapi" value="楽天トラベル"> 楽天トラベル　
        <input type="radio" name="usuapi" value="じゃらん"> じゃらん　
        <input type="radio" name="usuapi" value="Googleプレイス（Google Maps）"> Googleプレイス（Google Maps）
        <br>
        <input type="radio" name="usuapi" value="楽天市場"> 楽天市場タグ検索商品表示（β）
        <input type="radio" name="usuapi" value="バリューコマース"> バリューコマース検索商品表示（β）
        <input type="radio" name="usuapi" value="Amazon.co.jp"> Amazon.co.jp検索商品表示（β）
EOS;
        break;
        
        case 'ぐるなび':
        echo <<< EOS
        <input type="radio" name="usuapi" value="ホットペッパー" > ホットペッパー　
        <input type="radio" name="usuapi" value="ぐるなび" checked> ぐるなび　
        <input type="radio" name="usuapi" value="楽天トラベル"> 楽天トラベル　
        <input type="radio" name="usuapi" value="じゃらん"> じゃらん　
        <input type="radio" name="usuapi" value="Googleプレイス（Google Maps）"> Googleプレイス（Google Maps）
                <br>
        <input type="radio" name="usuapi" value="楽天市場"> 楽天市場タグ検索商品表示（β）
        <input type="radio" name="usuapi" value="バリューコマース"> バリューコマース検索商品表示（β）
        <input type="radio" name="usuapi" value="Amazon.co.jp"> Amazon.co.jp検索商品表示（β）
EOS;
        break;

        case '楽天トラベル':
        echo <<< EOS
        <input type="radio" name="usuapi" value="ホットペッパー" > ホットペッパー　
        <input type="radio" name="usuapi" value="ぐるなび"> ぐるなび　
        <input type="radio" name="usuapi" value="楽天トラベル" checked> 楽天トラベル　
        <input type="radio" name="usuapi" value="じゃらん"> じゃらん　
        <input type="radio" name="usuapi" value="Googleプレイス（Google Maps）"> Googleプレイス（Google Maps）
                <br>
        <input type="radio" name="usuapi" value="楽天市場"> 楽天市場タグ検索商品表示（β）
        <input type="radio" name="usuapi" value="バリューコマース"> バリューコマース検索商品表示（β）
        <input type="radio" name="usuapi" value="Amazon.co.jp"> Amazon.co.jp検索商品表示（β）
EOS;
        break;

        case 'じゃらん':
        echo <<< EOS
        <input type="radio" name="usuapi" value="ホットペッパー" > ホットペッパー　
        <input type="radio" name="usuapi" value="ぐるなび"> ぐるなび　
        <input type="radio" name="usuapi" value="楽天トラベル"> 楽天トラベル　
        <input type="radio" name="usuapi" value="じゃらん" checked> じゃらん　
        <input type="radio" name="usuapi" value="Googleプレイス（Google Maps）"> Googleプレイス（Google Maps）
                <br>
        <input type="radio" name="usuapi" value="楽天市場"> 楽天市場タグ検索商品表示（β）
        <input type="radio" name="usuapi" value="バリューコマース"> バリューコマース検索商品表示（β）
        <input type="radio" name="usuapi" value="Amazon.co.jp"> Amazon.co.jp検索商品表示（β）
EOS;
        break;
        case 'じゃらん':
        echo <<< EOS
        <input type="radio" name="usuapi" value="ホットペッパー" > ホットペッパー　
        <input type="radio" name="usuapi" value="ぐるなび"> ぐるなび　
        <input type="radio" name="usuapi" value="楽天トラベル"> 楽天トラベル　
        <input type="radio" name="usuapi" value="じゃらん" checked> じゃらん　
        <input type="radio" name="usuapi" value="Googleプレイス（Google Maps）"> Googleプレイス（Google Maps）
                <br>
        <input type="radio" name="usuapi" value="楽天市場"> 楽天市場タグ検索商品表示（β）
        <input type="radio" name="usuapi" value="バリューコマース"> バリューコマース検索商品表示（β）
        <input type="radio" name="usuapi" value="Amazon.co.jp"> Amazon.co.jp検索商品表示（β）
EOS;
        break;

        case 'Googleプレイス（Google Maps）':
        echo <<< EOS
        <input type="radio" name="usuapi" value="ホットペッパー" > ホットペッパー　
        <input type="radio" name="usuapi" value="ぐるなび"> ぐるなび　
        <input type="radio" name="usuapi" value="楽天トラベル"> 楽天トラベル　
        <input type="radio" name="usuapi" value="じゃらん"> じゃらん　
        <input type="radio" name="usuapi" value="Googleプレイス（Google Maps）" checked> Googleプレイス（Google Maps）
                <br>
        <input type="radio" name="usuapi" value="楽天市場"> 楽天市場タグ検索商品表示（β）
        <input type="radio" name="usuapi" value="バリューコマース"> バリューコマース検索商品表示（β）
        <input type="radio" name="usuapi" value="Amazon.co.jp"> Amazon.co.jp検索商品表示（β）
EOS;
        break;

        case '楽天市場':
        echo <<< EOS
        <input type="radio" name="usuapi" value="ホットペッパー" > ホットペッパー　
        <input type="radio" name="usuapi" value="ぐるなび"> ぐるなび　
        <input type="radio" name="usuapi" value="楽天トラベル"> 楽天トラベル　
        <input type="radio" name="usuapi" value="じゃらん"> じゃらん　
        <input type="radio" name="usuapi" value="Googleプレイス（Google Maps）"> Googleプレイス（Google Maps）
                <br>
        <input type="radio" name="usuapi" value="楽天市場" checked> 楽天市場タグ検索商品表示（β）
        <input type="radio" name="usuapi" value="バリューコマース"> バリューコマース検索商品表示（β）
        <input type="radio" name="usuapi" value="Amazon.co.jp"> Amazon.co.jp検索商品表示（β）
EOS;
        break;

        case 'バリューコマース':
        echo <<< EOS
        <input type="radio" name="usuapi" value="ホットペッパー" > ホットペッパー　
        <input type="radio" name="usuapi" value="ぐるなび"> ぐるなび　
        <input type="radio" name="usuapi" value="楽天トラベル"> 楽天トラベル　
        <input type="radio" name="usuapi" value="じゃらん"> じゃらん　
        <input type="radio" name="usuapi" value="Googleプレイス（Google Maps）"> Googleプレイス（Google Maps）
                <br>
        <input type="radio" name="usuapi" value="楽天市場"> 楽天市場タグ検索商品表示（β）
        <input type="radio" name="usuapi" value="バリューコマース" checked> バリューコマース検索商品表示（β）
        <input type="radio" name="usuapi" value="Amazon.co.jp"> Amazon.co.jp検索商品表示（β）
EOS;
        break;
    
        case 'Amazon.co.jp':
        echo <<< EOS
        <input type="radio" name="usuapi" value="ホットペッパー" > ホットペッパー　
        <input type="radio" name="usuapi" value="ぐるなび"> ぐるなび　
        <input type="radio" name="usuapi" value="楽天トラベル"> 楽天トラベル　
        <input type="radio" name="usuapi" value="じゃらん"> じゃらん　
        <input type="radio" name="usuapi" value="Googleプレイス（Google Maps）"> Googleプレイス（Google Maps）
                <br>
        <input type="radio" name="usuapi" value="楽天市場"> 楽天市場タグ検索商品表示（β）
        <input type="radio" name="usuapi" value="バリューコマース"> バリューコマース検索商品表示（β）
        <input type="radio" name="usuapi" value="Amazon.co.jp" checked> Amazon.co.jp検索商品表示（β）
EOS;
        break;
}

echo <<< EOS
        <br>
        <input type="hidden" name="tab" value="locketsSearch">
        <input type="hidden" name="type" value="locketsSearch">
        <input type="hidden" name="TB_iframe" value="true">
        <input type="hidden" name="width" value="600">
        <input type="hidden" name="height" value="550">
        <input type="submit" value="検索" class="button button-primary" /> 
    </form>
</div>
EOS;

if ($searchword !== "") {

$url4searchword=urlencode($searchword);

switch ($useapi) {
    case 'ホットペッパー':
        $recruit_webservice_key= get_option('recruit_webservice_key');
        $recruiturl="http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key=$recruit_webservice_key&name=$url4searchword&datum=world";
        
        $Buff = get_transient( $recruiturl );
        if ( $Buff === false ) {
            $Buff = file_get_contents($recruiturl);
            set_transient( $shopid, $Buff, 3600 * 24 );
        }
        
        $xml = simplexml_load_string($Buff);
        $shops = $xml->shop;
        if ($shops) {
        echo "<form action='' id='hotpepperresult'><ul>";
        foreach ($shops as $shop) {
            echo "<li><input type='button' id='".locketsh($shop->id)."' value='挿入' class='button'>　<a href='".locketsh($shop->urls->pc)."' target='_blank'>".locketsh($shop->name)."</a>（".locketsh($shop->id)."）</li>";
        }
        echo '<li>Powered by <a href="http://webservice.recruit.co.jp/">ホットペッパー Webサービス</a></li>';
        echo "</ul></form>";
             } else {
            echo "<form id='noresult'><p>検索結果はありませんでした。キーワードもしくは検索対象を切り替えて試してみてください。</p></form>";
        }
    break;

    case 'ぐるなび':
        $gnavi_webservice_key= get_option('gnavi_webservice_key');
        $gurunaviurl="https://api.gnavi.co.jp/RestSearchAPI/v3/?keyid=$gnavi_webservice_key&name=$url4searchword&coordinates_mode=2";

        $Buff = get_transient( $gurunaviurl );
        if ( $Buff === false ) {
            $Buff = file_get_contents($gurunaviurl);
            set_transient( $gurunaviurl, $Buff, 3600 * 24 );
        }
        $json = json_decode($Buff);
        $shops = $json->rest;
        if ($shops) {
        echo "<form action='' id='gurunaviresult'><ul>";
        foreach ($shops as $shop) {
            echo "<li><input type='button' id='".locketsh($shop->id)."' value='挿入' class='button'>　<a href='".locketsh($shop->url)."' target='_blank'>".locketsh($shop->name)."</a>（".locketsh($shop->id)."）</li>";
        }
        echo '<li>Supported by <a href="http://api.gnavi.co.jp/api/scope/" target="_blank">ぐるなびWebService</a></li>';
        echo "</ul></form>";
            } else {
            echo "<form id='noresult'><p>検索結果はありませんでした。キーワードもしくは検索対象を切り替えて試してみてください。</p></form>";
        }
    break;

    case '楽天トラベル':
        $rakutentoken= get_option('rakuten_search_token');
        $rakutenaffid= get_option('rakuten_affiliate_id');
        $rwsurl="https://app.rakuten.co.jp/services/api/Travel/KeywordHotelSearch/20170426?applicationId=$rakutentoken&affiliateId=$rakutenaffid&format=xml&keyword=$url4searchword&datumType=1";

        $Buff = get_transient( $rwsurl );
        if ( $Buff === false ) {
            $options['ssl']['verify_peer']=false;
            $options['ssl']['verify_peer_name']=false;
            if ($Buff = @file_get_contents($rwsurl,false, stream_context_create($options))) {
                $rakutentravelerror = "1";
                set_transient( $rwsurl, $Buff, 3600 * 24 );
            }
        }

        $xml = simplexml_load_string($Buff);
        $hotels = $xml->hotels->hotel;
        $resultcount = $xml->pagingInfo->recordCount;
        
        if ($hotels) {
            echo "<form action='' id='rakutentravelresult'><ul>";
            foreach ($hotels as $hotel) {
                echo "<li><input type='button' id='".locketsh($hotel->hotelBasicInfo->hotelNo)."' value='挿入' class='button'>　<a href='".locketsh($hotel->hotelBasicInfo->hotelInformationUrl)."' target='_blank'>".locketsh($hotel->hotelBasicInfo->hotelName)."</a>（".locketsh($hotel->hotelBasicInfo->hotelNo)."）</li>";
            }
            echo '<li><!-- Rakuten Web Services Attribution Snippet FROM HERE -->
<a href="https://webservice.rakuten.co.jp/" target="_blank">Supported by 楽天ウェブサービス</a>
<!-- Rakuten Web Services Attribution Snippet TO HERE --></li>';
            echo "</ul></form>";
        } else {
            echo "<form id='noresult'><p>検索結果はありませんでした。キーワードもしくは検索対象を切り替えて試してみてください。</p></form>";
        }
    break;

    case 'じゃらん':
        $jalan_webservice_key= get_option('jalan_webservice_key');
        $jalanurl="http://jws.jalan.net/APIAdvance/HotelSearch/V1/?key=$jalan_webservice_key&h_name=$url4searchword";

        // キャッシュ有無確認
        $Buff = get_transient($jalanurl);
        if ( $Buff === false ) {
            $Buff = file_get_contents($jalanurl);
            set_transient($jalanurl, $Buff, 3600 * 24 );
        }

        $xml = @simplexml_load_string($Buff);//warning防止
        $jalanhotel = $xml->Hotel;

        if ($jalanhotel) {
        echo "<form action='' id='jalanresult'><ul>";
        foreach ($jalanhotel as $hotel) {
            echo "<li><input type='button' id='".locketsh($hotel->HotelID)."' value='挿入' class='button'>　<a href='".locketsh($hotel->HotelDetailURL)."' target='_blank'>".locketsh($hotel->HotelName)."</a>（".locketsh($hotel->HotelID)."）</li>";
        }
        echo '<li><a href="http://www.jalan.net/jw/jwp0000/jww0001.do">じゃらん Web サービス</a></li>';
        echo "</ul></form>";
            } else {
            echo "<form id='noresult'><p>検索結果はありませんでした。キーワードもしくは検索対象を切り替えて試してみてください。</p></form>";
        }

    break;
        
    case 'Googleプレイス（Google Maps）':
        $lockets_gmap_apikey= get_option('lockets_gmap_apikey');
        $gmapurl="https://maps.googleapis.com/maps/api/place/textsearch/xml?key=$lockets_gmap_apikey&query=$url4searchword&language=ja";

        $Buff = file_get_contents($gmapurl);//キャッシュ使わない
        $xml = simplexml_load_string($Buff);
        // print_r($xml);//エラー確認用
        $gmapplaces = $xml->result;

        if ($gmapplaces) {
        echo "<form action='' id='gmapplaceresult'><ul>";
        foreach ($gmapplaces as $place) {
            echo "<li><input type='button' value='挿入' class='button' id='".locketsh($place->place_id)."'>　<a href='"."https://www.google.com/maps/embed/v1/place?q=place_id:".locketsh($place->place_id)."&key=".$lockets_gmap_apikey."' target='_blank'>".locketsh($place->name)."</a>（".locketsh($place->id)."）</li>";
        }
        echo '<li><a href="https://developers.google.com/places/web-service/?hl=ja">Places API Web Service</a> <a href="https://developers.google.com/maps/documentation/embed/?hl=ja">Maps Embed API</a></li><li><img src="'.WP_PLUGIN_URL.'/lockets/images/powered_by_google_on_white.png"></li>';
        echo "</ul></form>";
            } else {
            echo "<form id='noresult'><p>検索結果はありませんでした。キーワードもしくは検索対象を切り替えて試してみてください。</p></form>";
        }

    break;
        
    case '楽天市場':
        $rakutentoken= get_option('rakuten_search_token');
        $rakutenaffid= get_option('rakuten_affiliate_id');
        $rwsurl="https://app.rakuten.co.jp/services/api/IchibaItem/Search/20170706?applicationId=$rakutentoken&affiliateId=$rakutenaffid&format=xml&keyword=$url4searchword";

        $Buff = get_transient( $rwsurl );
        if ( $Buff === false ) {
            $options['ssl']['verify_peer']=false;
            $options['ssl']['verify_peer_name']=false;
            if ($Buff = @file_get_contents($rwsurl,false, stream_context_create($options))) {
                $rakutenitemerror = "1";
                set_transient( $rwsurl, $Buff, 3600 * 24 );
            }
        }

        $xml = simplexml_load_string($Buff);
        $items = $xml->Items->Item;
        $resultcount = $xml->count;

        if ($items) {
        echo "<form action='' id='rakutenitemresult'><ul>";
        foreach ($items as $item) {
            echo "<li id='".locketsh($item->itemCode)."'><input type='button' value='挿入' class='button'>　<a href='".locketsh($item->itemUrl)."' target='_blank'>".locketsh($item->itemName)."</a><br>（商品ID：".locketsh($item->itemCode)."　タグ：<span>".locketsh($item->tagIds->value)."</span>）</li>";
        }
        echo '<li><!-- Rakuten Web Services Attribution Snippet FROM HERE -->
<a href="https://webservice.rakuten.co.jp/" target="_blank">Supported by 楽天ウェブサービス</a>
<!-- Rakuten Web Services Attribution Snippet TO HERE --></li>';
        echo "</ul></form>";
            } else {
            echo "<form id='noresult'><p>検索結果はありませんでした。キーワードもしくは検索対象を切り替えて試してみてください。</p></form>";
        }

    break;

    case 'バリューコマース':
        $lockets_valuecommerce_token = get_option('lockets_valuecommerce_token');
        $vcurl = "http://webservice.valuecommerce.ne.jp/productdb/search?token=$lockets_valuecommerce_token&keyword=$url4searchword";
//echo $vcurl;
        $Buff = get_transient( $vcurl );
        if ( $Buff === false ) {
            $options['ssl']['verify_peer']=false;
            $options['ssl']['verify_peer_name']=false;
            if ($Buff = @file_get_contents($vcurl,false, stream_context_create($options))) {
                $rakutenitemerror = "1";
                set_transient( $vcurl, $Buff, 3600 * 24 );
            }
        }

        $Buff = str_replace('vc:', 'vc', $Buff);
        $Buff = str_replace('&', '&amp;', $Buff);
        $xml = simplexml_load_string($Buff);
        $items = $xml->channel->item;
        $resultcount = locketsh($xml->channel->vcresultcount);
        $totalpage = locketsh($xml->channel->vcpagecount);

        if ($items) {
        echo "<form action='' id='valuecommerceitemresult'><ul>";
        foreach ($items as $item) {
            echo "<li id='".locketsh($item->vcproductCode)."'><input type='button' value='挿入' class='button'>　<a href='".locketsh($item->link)."' target='_blank'>".locketsh($item->title)."</a><br>（商品ID：".locketsh($item->vcproductCode)."　JANコード：<span>".locketsh($item->vcjanCode)."</span>）</li>";
        }
        echo "</ul></form>";
            } else {
            echo "<form id='noresult'><p>検索結果はありませんでした。キーワードもしくは検索対象を切り替えて試してみてください。</p></form>";
        }

    break;
        
    case 'Amazon.co.jp':

        $lockets_Amazonitemsearchtapi= new lockets_Amazonitemsearchtapi();
        $awsurl = $lockets_Amazonitemsearchtapi->awsrequesturl($searchword);
        
        $Buff = get_transient( $awsurl );
        if ( $Buff === false ) {
            if ($Buff = file_get_contents($awsurl,false, stream_context_create($options))) {
                $rakutenitemerror = "1";
                set_transient( $awsurl, $Buff, 3600 * 24 );
            }
        }

        $xml = simplexml_load_string($Buff);
        $items = $xml->Items->Item;
        $resultcount = locketsh($xml->Items->TotalResults);
        $totalpage = locketsh($xml->Items->TotalPages);

        if ($items) {
        echo "<form action='' id='amazonitemresult'><ul>";
        foreach ($items as $item) {
            echo "<li id='".locketsh($item->ASIN)."'><input type='button' value='挿入' class='button'>　<a href='".locketsh($item->DetailPageURL)."' target='_blank'>".locketsh($item->ItemAttributes->Title)."</a><br>（ASIN：".locketsh($item->ASIN)."　<span>"."</span>）</li>";
        }
        echo "</ul></form>";
            } else {
            echo "<form id='noresult'><p>検索結果はありませんでした。キーワードもしくは検索対象を切り替えて試してみてください。</p></form>";
        }

    break;
}


}

}

// jQuery
function lockets_head(){
		echo <<< EOS
			<script type="text/javascript">
			jQuery(function($) {
            $(document).ready(function() {
                $('#gurunaviresult input').on('click', function() {
                    top.send_to_editor( '[LocketsGurunavi shopid="' + this.id + '"]');
                    top.tb_remove(); 
                });
                $('#hotpepperresult input').on('click', function() {
                    top.send_to_editor( '[LocketsHotpepper shopid="' + this.id + '"]');
                    top.tb_remove(); 
                });
                $('#rakutentravelresult input').on('click', function() {
                    top.send_to_editor( '[LocketsRakutenTravel hotelno="' + this.id + '"]');
                    top.tb_remove(); 
                });
                $('#jalanresult input').on('click', function() {
                    top.send_to_editor( '[LocketsJalan hotelno="' + this.id + '"]');
                    top.tb_remove(); 
                });
                $('#gmapplaceresult input').on('click', function() {
                    top.send_to_editor( '[LocketsGMaps placeid="' + this.id + '"]');
                    top.tb_remove(); 
                });
                $('#rakutenitemresult input').on('click', function() {
                var itemid = $(this).parent('li').attr('id');
                var tagid = $(this).parent('li').children('span').text();
                    top.send_to_editor( '[LocketsRakutenItem itemcode="' + itemid + '" tagid="'+ tagid +'"]');
                    top.tb_remove(); 
                });
                $('#valuecommerceitemresult input').on('click', function() {
                var itemid = $(this).parent('li').attr('id');
                var tagid = $(this).parent('li').children('span').text();
                    top.send_to_editor( '[LocketsValuecommerceItem itemcode="' + itemid + '" jancode="'+ tagid +'"]');
                    top.tb_remove(); 
                });
                $('#amazonitemresult input').on('click', function() {
                var asin = $(this).parent('li').attr('id');
                //var tagid = $(this).parent('li').children('span').text();
                    top.send_to_editor( '[LocketsAmazonItem asin="' + asin + '"]');
                    top.tb_remove(); 
                });

                });
            })
            </script>
EOS;
		}


//　タブ
function lockets_upload_tabs( $tabs )
{
	$tabs=array();
    $tabs[ "locketsSearch" ] = "検索と記事への挿入" ;
	return $tabs;
}

// Lockets配信用フォーマット

$locketsfeedswitch = get_option('locketsfeedswitch');
$lockets_feedurl = get_option('lockets_feedurl');
if($locketsfeedswitch == "1"){
    function do_feed_lctfmt() {
        $feed_template = WP_PLUGIN_DIR . '/lockets/feeds/lctfmt.php';
        load_template( $feed_template );
    }
    add_action( 'do_feed_'.$lockets_feedurl , 'do_feed_lctfmt');
}

?>