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
update_option('lockets_kanrenfeed', esc_js($_POST['lockets_kanrenfeed']));
update_option('lockets_classfeed', esc_js($_POST['lockets_classfeed']));
?>

<div class="updated fade">
    <p><strong><?php _e('Options saved.'); ?></strong></p>
</div>
<?php }
$lockets_feedswitch = get_option('lockets_feedswitch');
$lockets_feedurl = get_option('lockets_feedurl');
$lockets_feedua = get_option('lockets_feedua');
$lockets_feedlogourl = get_option('lockets_feedlogourl');
$lockets_kanrenfeed = get_option('lockets_kanrenfeed');
$lockets_classfeed = get_option('lockets_classfeed');
?>

<div class="wrap">
    <h2>Locketsプラグイン：外部へのfeed配信β＜開発中/テスター向け＞</h2>
    <p>外部サイトへ配信するfeedを有効にする場合はONとしてください。LocketsおよびSmartFormatに準拠したfeedを出力します（β版）。</p>
    <form name="form" method="post" action="">
        <input type="hidden" name="action" value="update" />
        <?php wp_nonce_field('lockets-options'); ?>

        <table class="form-table">
            <tbody>

                <tr>
                    <th><label for="locketsfeedswitch"><?php
_e('外部配信を有効にする', 'lockets_feedswitch'); ?></label></th>
                    <td>
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
                        <?php if ($lockets_feedurl == "" or $lockets_feedurl == null) { } else { ?>
                    設定したURLは“ <a href="<?php echo site_url()."/?feed=".attribute_escape($lockets_feedurl); ?>"><?php echo site_url()."/?feed=".attribute_escape($lockets_feedurl); ?></a> ”です
                        <?php } ?>
                    </td>
                </tr>

                <tr>
                    <th><label for="lockets_feedlogourl"><?php
_e('logoの画像URL', 'lockets_feedlogourl'); ?></label></th>
                    <td><input size="36" type="text" name="lockets_feedlogourl" id="lockets_feedlogourl" value="<?php
echo attribute_escape($lockets_feedlogourl); ?>" /><br>サイトロゴのURLをhttpから入れてください。「横700px以下 x 縦およそ100px」で背景が透明になっているPNGファイルを推奨しています。</td>
                </tr>
                
                <tr>
                    <th><label for="lockets_feedua"><?php
_e('SmartView上に入れるGoogle AnalyticsのUA番号', 'lockets_feedua'); ?></label></th>
                    <td><input size="36" type="text" name="lockets_feedua" id="lockets_feedua" value="<?php
echo attribute_escape($lockets_feedua); ?>" /><br>「UA-xxxxxxx-x」を入れてください。</td>
                </tr>

                <tr>
                    <th><label for="lockets_kanrenfeed"><?php
    _e('関連記事等以後feedから削除したい文言', 'lockets_kanrenfeed'); ?></label></th>
                    <td><input size="36" type="text" name="lockets_kanrenfeed" id="lockets_kanrenfeed" value="<?php
    echo attribute_escape($lockets_kanrenfeed); ?>" /><br>関連記事等、この文言以降の本文はfeedに含めない設定。</td>
                </tr>

                <tr>
                    <th><label for="lockets_classfeed"><?php
    _e('ブログカードなど削除したいclass名', 'lockets_classfeed'); ?></label></th>
                    <td><input size="36" type="text" name="lockets_classfeed" id="lockets_classfeed" value="<?php
    echo attribute_escape($lockets_classfeed); ?>" /><br>このclass名を含んだブロックは削除する</td>
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