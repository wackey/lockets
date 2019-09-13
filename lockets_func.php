<?php
/* HTML無効化 */
// 追加機能：コメントタグっぽいものはコメントタグに戻します。
function locketsh($str)
{
    $str=htmlspecialchars($str, ENT_QUOTES);
	$str=str_replace("&amp;lt;!--","<!--",$str);
	$str=str_replace("--&amp;gt; ","-->",$str);
    $str=str_replace("&lt;BR&gt;","<br>",$str);
    $str=str_replace("&amp;","&",$str);
	return $str;
}

/* scriptタグ削除 */
function lockets_remove_script_tag($target) {
	return mb_eregi_replace('/<script.*<\/script>/', '', $target);
}

// Google Maps表示タグ出力（各API共通）
function lockets_gmap_draw($keyword,$lat,$lng,$zoom,$width,$height) {
    $lockets_gmap_zoom= get_option('lockets_gmap_zoom');
    $lockets_gmap_width= get_option('lockets_gmap_width');
    $lockets_gmap_height= get_option('lockets_gmap_height');
    if ($zoom == "") {
        if ($lockets_gmap_zoom == "") {$zoom="14";} else {$zoom=$lockets_gmap_zoom;}
        }
    if ($width == "") {
        if ($lockets_gmap_width == "") {$width="100%";} else {$width=$lockets_gmap_width;}
        }
    if ($height == "") {
        if ($lockets_gmap_height == "") {$height="450";} else {$height=$lockets_gmap_height;}
        }
    if ($lat == "") {return "";} else {
    $ret = '<iframe src="https://maps.google.co.jp/maps?q='.$keyword.'&ll='.$lat.','.$lng.'&output=embed&t=m&z='.$zoom.'&hl=ja" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="'.$width.'" height="'.$height.'"></iframe>';
    return $ret;}
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


function locketsSearch_wp_iframe() {
        wp_iframe( 'media_upload_lockets2_form' );
}


//　タブ
function lockets_upload_tabs( $tabs )
{
	$tabs=array();
    $tabs[ "locketsSearch" ] = "検索と記事への挿入" ;
	return $tabs;
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
?>