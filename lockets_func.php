<?php
/* HTML無効化 */
// 追加機能：コメントタグっぽいものはコメントタグに戻します。
function locketsh($str)
{
    $str=htmlspecialchars($str, ENT_QUOTES);
	$str=str_replace("&amp;lt;!--","<!--",$str);
	$str=str_replace("--&amp;gt; ","-->",$str);
    $str=str_replace("&lt;BR&gt;","<br>",$str);
	return $str;
}

/* scriptタグ削除 */
function lockets_remove_script_tag($target) {
	return mb_eregi_replace('/<script.*<\/script>/', '', $target);
}
?>
