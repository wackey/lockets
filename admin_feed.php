<?php
// 外部feed配信
function lockets_feed() {
// ポストされた値の入力チェックと書き込み
if (isset($_POST['update_option'])) {
check_admin_referer('lockets-options');
if($_POST['lockets_feedswitch'] == 1){
update_option('lockets_feedswitch', "1");
} else {
update_option('lockets_feedswitch', "0");
}
update_option('lockets_feedurl', sanitize_text_field($_POST['lockets_feedurl']));
update_option('lockets_feedua', sanitize_text_field($_POST['lockets_feedua']));
update_option('lockets_feedlogourl', sanitize_text_field($_POST['lockets_feedlogourl']));
?>
<div class="updated fade">
    <p><strong><?php _e('Options saved.'); ?></strong></p>
</div>
<?php }
$lockets_feedswitch = get_option('lockets_feedswitch');
$lockets_feedurl = get_option('lockets_feedurl');
$lockets_feedua = get_option('lockets_feedua');
$lockets_feedlogourl = get_option('lockets_feedlogourl');
?>

<div class="wrap">
    <h2>Locketsプラグイン：外部へのfeed配信β＜開発中/テスター向け＞</h2>
    <p>外部サイトへ配信するfeedを有効にする場合はONとしてください</p>
    <form name="form" method="post" action="">
        <input type="hidden" name="action" value="update" />
        <?php wp_nonce_field('lockets-options'); ?>

        <table class="form-table">
            <tbody>

                <tr>
                    <th><label for="locketsfeedswitch"><?php
_e('外部配信を有効にする', 'lockets_feedswitch'); ?></label></th>
                    <td><? echo $lockets_feedswitch; ?>
                        <?php if($lockets_feedswitch == "1"){ ?>
                        <input type="radio" name="lockets_feedswitch" value="1" checked>ON
                        <input type="radio" name="lockets_feedswitch" value="0">OFF
                        <?php }else{ ?>
                        <input type="radio" name="lockets_feedswitch" value="1">ON
                        <input type="radio" name="lockets_feedswitch" value="0" checked>OFF
                        <?php } ?>
                    </td>
                </tr>

                <tr>
                    <th><label for="lockets_feedurl"><?php
_e('外部配信用URL設定', 'lockets_feedurl'); ?></label></th>
                    <td><input size="36" type="text" name="lockets_feedurl" id="lockets_feedurl" value="<?php
echo attribute_escape($lockets_feedurl); ?>" /><br>
                    <?php echo site_url()."/?feed=".attribute_escape($lockets_feedurl); ?></td>
                </tr>

                <tr>
                    <th><label for="lockets_feedlogourl"><?php
_e('logoの画像URL', 'lockets_feedlogourl'); ?></label></th>
                    <td><input size="36" type="text" name="lockets_feedlogourl" id="lockets_feedlogourl" value="<?php
echo attribute_escape($lockets_feedlogourl); ?>" /><br>logoのURLをhttpから入れてください。</td>
                </tr>
                
                <tr>
                    <th><label for="lockets_feedua"><?php
_e('SmartView上に入れるGoogle AnalyticsのUA番号', 'lockets_feedua'); ?></label></th>
                    <td><input size="36" type="text" name="lockets_feedua" id="lockets_feedua" value="<?php
echo attribute_escape($lockets_feedua); ?>" /><br>「UA-xxxxxxx-x」を入れてください。</td>
                </tr>

            </tbody>
        </table>

        <p class="submit">
            <input type="submit" name="update_option" class="button-primary" value="<?php _e('Save Changes'); ?>" />
        </p>

    </form>
</div>

<?php
}
?>