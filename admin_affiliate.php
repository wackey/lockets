<?php
// その他アフィリエイト設定
function lockets_affiliate() {
// ポストされた値の入力チェックと書き込み
if (isset($_POST['update_option'])) {
check_admin_referer('lockets-options');
update_option('valuecommerce_pid', sanitize_text_field($_POST['valuecommerce_pid']));
update_option('lockets_valuecommerce_token', sanitize_text_field($_POST['lockets_valuecommerce_token']));
update_option('lockets_linkshare_token', sanitize_text_field($_POST['lockets_linkshare_token']));
?>
<div class="updated fade"><p><strong><?php _e('Options saved.'); ?></strong></p>
</div>
<?php }
$valuecommerce_pid = get_option('valuecommerce_pid');
$lockets_valuecommerce_token = get_option('lockets_valuecommerce_token');
$lockets_linkshare_token = get_option('lockets_linkshare_token');
?>

<div class="wrap">
<h2>Locketsプラグイン：その他アフィリエイト設定</h2>
<p>その他アフィリエイト設定するための設定です。</p>
<p>バリューコマースpidを入力するとLinkSwitch機能が有効になります。</p>
<form name="form" method="post" action="">
<input type="hidden" name="action" value="update" />
<?php wp_nonce_field('lockets-options'); ?>

<table class="form-table"><tbody>

<tr>
<th><label for="valuecommerce_pid"><?php
_e('バリューコマース pid', 'valuecommerce_pid'); ?></label></th> <td><input size="36" type="text" name="valuecommerce_pid"
id="valuecommerce_pid" value="<?php
echo attribute_escape($valuecommerce_pid); ?>" /></td>
</tr>
<tr>
<th><label for="lockets_valuecommerce_token"><?php
_e('バリューコマーストークン（token）', 'lockets_valuecommerce_token'); ?></label></th> <td><input size="36" type="text" name="lockets_valuecommerce_token"
id="lockets_valuecommerce_token" value="<?php
echo attribute_escape($lockets_valuecommerce_token); ?>" /></td>
</tr>
<tr>
<th><label for="lockets_linkshare_token"><?php
_e('リンクシェアトークン（token）', 'lockets_linkshare_token'); ?></label></th> <td><input size="36" type="text" name="lockets_linkshare_token"
id="lockets_linkshare_token" value="<?php
echo attribute_escape($lockets_linkshare_token); ?>" /></td>
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