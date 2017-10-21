<?php
// Google Maps表示設定
function lockets_gmap() {
// ポストされた値の入力チェックと書き込み
if (isset($_POST['update_option'])) {
    check_admin_referer('lockets-options');
    update_option('lockets_gmap_apikey', sanitize_text_field($_POST['lockets_gmap_apikey']));
    update_option('lockets_gmap_zoom', sanitize_text_field($_POST['lockets_gmap_zoom']));
    update_option('lockets_gmap_width', sanitize_text_field($_POST['lockets_gmap_width']));
    update_option('lockets_gmap_height', sanitize_text_field($_POST['lockets_gmap_height']));
?>
<div class="updated fade"><p><strong><?php _e('Options saved.'); ?></strong></p>
</div>
<?php }
    $lockets_gmap_apikey= get_option('lockets_gmap_apikey');
    $lockets_gmap_zoom= get_option('lockets_gmap_zoom');
    $lockets_gmap_width= get_option('lockets_gmap_width');
    $lockets_gmap_height= get_option('lockets_gmap_height');
?>

<div class="wrap">
<h2>Google Maps表示設定</h2>
<p>Google Mapsの幅や高さ、ズームのデフォルト値をこちらで設定します。<br>
※投稿ごとに個別に設定することも可能です。<br>
※未入力の場合はデフォルト設定のままでご使用いただけます。</p>
<form name="form" method="post" action="">
<input type="hidden" name="action" value="update" />
<?php wp_nonce_field('lockets-options'); ?>

<table class="form-table"><tbody>
    
<tr>
<th><label for="lockets_gmap_apikey"><?php
_e('Google Maps APIキー', 'loclets_gmap_apikey'); ?></label></th> <td><input size="36" type="text" name="lockets_gmap_apikey"
id="lockets_gmap_apikey" value="<?php
echo attribute_escape($lockets_gmap_apikey); ?>" /></td>
</tr>

<tr>
<th><label for="lockets_gmap_zoom"><?php
_e('Google Mapsのズーム値', 'loclets_gmap_zoom'); ?></label></th> <td><input size="36" type="text" name="lockets_gmap_zoom"
id="lockets_gmap_zoom" value="<?php
echo attribute_escape($lockets_gmap_zoom); ?>" /></td>
</tr>

<tr>
<th><label for="lockets_gmap_width"><?php
_e('Google Mapsの幅', 'lockets_gmap_width'); ?></label></th> <td><input size="36" type="text" name="lockets_gmap_width"
id="lockets_gmap_width" value="<?php
echo attribute_escape($lockets_gmap_width); ?>" /></td>
</tr>
    
<tr>
<th><label for="lockets_gmap_height"><?php
_e('Google Mapsの高さ', 'lockets_gmap_height'); ?></label></th> <td><input size="36" type="text" name="lockets_gmap_height"
id="lockets_gmap_height" value="<?php
echo attribute_escape($lockets_gmap_height); ?>" /></td>
</tr>
    
</tbody></table>

<p class="submit">
<input type="submit" name="update_option" class="button-primary" value="<?php _e('Save Changes'); ?>" />
</p>

</form>
</div>

<?php
}
?>