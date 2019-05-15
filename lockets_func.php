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
    $ret = '<iframe src="https://maps.google.co.jp/maps?q='.$keyword.'&ll='.$lat.','.$lng.'&output=embed&t=m&z='.$zoom.'&hl=ja" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="'.$width.'" height="'.$height.'"></iframe>';
    return $ret;
}

?>
