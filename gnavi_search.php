<?php
// ぐるなび検索・暫定
function lockets_gnavi_searchbox() {
// ポストされた値の入力チェックと書き込み
if (isset($_POST['update_option'])) {
check_admin_referer('lockets-options');
    
?>

<?php }
$gnavi_shopname=sanitize_text_field($_POST['gnavi_shopname']);
$gnavi_webservice_key= get_option('gnavi_webservice_key');
?>

<div class="wrap">
<h2>ぐるなび検索（暫定）</h2>
<p>ぐるなびを検索結果を表示するための設定です</p>
<form name="form" method="post" action="">
<input type="hidden" name="action" value="update" />
<?php wp_nonce_field('lockets-options'); ?>

<table class="form-table"><tbody>

<tr>
<th><label for="gnavi_shopname"><?php
_e('ぐるなび店舗名', 'gnavi_shopname'); ?></label></th> <td><input size="36" type="text" name="gnavi_shopname"
id="gnavi_shopname" value="<?php
echo attribute_escape($gnavi_shopname); ?>" /></td>
</tr>


</tbody></table>

<p class="submit">
<input type="submit" name="update_option" class="button-primary" value="<?php _e('Search'); ?>" />
</p>

</form>


    
<p>
<?php
// リクエストURL
$gurunaviurl="https://api.gnavi.co.jp/RestSearchAPI/20150630/?keyid=$gnavi_webservice_key&format=xml&name=$gnavi_shopname&coordinates_mode=2";

// キャッシュ有無確認
$Buff = get_transient( $gurunaviurl );
if ( $Buff === false ) {
$Buff = file_get_contents($gurunaviurl);
set_transient( $gurunaviurl, $Buff, 3600 * 24 );
}

$xml = simplexml_load_string($Buff);
$shops = $xml->rest;
echo "<table><tr><td>店名</td><td>ID</td></tr>";
foreach ($shops as $shop) {
echo "<tr>";
echo "<td>".locketsh($shop->name)."</td>";
echo "<td>".locketsh($shop->id)."</td>";
echo "</tr>";
}
echo "</table>" 
    ?>
    
</p>    
</div>

<?php
}
?>