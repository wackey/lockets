<?php
/*
 Lockets format
 arr. by wackey.
*/

/**
 * RSS2 Feed Template for displaying RSS2 Posts feed.
 *
 * @package WordPress
 */
//require_once dirname(dirname(__FILE__)) . '/ng_filter.php';

// cf.https://qiita.com/lumbermill/items/3ca3a467313fbcafaf19
/*
$header = gelallheaders();
if ($header['Authorization'] == '2d60a728-17fd-4f94-bd85-f960ee037e97') {
  echo '認証OK';
}
*/

function http_basic_authenticate_with($name,$password,$realm = "Protected area"){
  if (!isset($_SERVER['PHP_AUTH_USER']) || !($_SERVER['PHP_AUTH_USER'] == $name && $_SERVER['PHP_AUTH_PW'] == $password )) {
    header('WWW-Authenticate: Basic realm="'.$realm.'"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Not allowed to access.';
    exit;
  }
}

$lockets_feedid = get_option('lockets_feedid');
$lockets_feedpass = get_option('lockets_feedpass');
http_basic_authenticate_with($lockets_feedid,$lockets_feedpass); 

header('Content-Type: ' . feed_content_type('rss2') . '; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';

/**
 * Fires between the xml and rss tags in a feed.
 *
 * @since 4.0.0
 *
 * @param string $context Type of feed. Possible values include 'rss2', 'rss2-comments',
 *                        'rdf', 'atom', and 'atom-comments'.
 */
do_action( 'rss_tag_pre', 'rss2' );
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
    xmlns:media="http://search.yahoo.com/mrss/"
    xmlns:snf="http://www.smartnews.be/snf"
    xmlns:georss="http://www.georss.org/georss"
    xmlns:gml="http://www.opengis.net/gml"
	<?php
	/**
	 * Fires at the end of the RSS root to add namespaces.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_ns' );
	?>
>

<channel>
	<title><?php wp_title_rss(); ?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss('url') ?></link>
	<description><?php bloginfo_rss("description") ?></description>
	<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></pubDate>
	<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<copyright>(C) Plus Movement LLC.</copyright>
	<ttl>5</ttl>
	<snf:logo><?php echo plugins_url( 'images/locketslogosf.png', dirname(__FILE__)); ?></snf:logo>
	<sy:updatePeriod><?php
		$duration = 'hourly';

		/**
		 * Filters how often to update the RSS feed.
		 *
		 * @since 2.1.0
		 *
		 * @param string $duration The update period. Accepts 'hourly', 'daily', 'weekly', 'monthly',
		 *                         'yearly'. Default 'hourly'.
		 */
		echo apply_filters( 'rss_update_period', $duration );
	?></sy:updatePeriod>
	<sy:updateFrequency><?php
		$frequency = '1';

		/**
		 * Filters the RSS update frequency.
		 *
		 * @since 2.1.0
		 *
		 * @param string $frequency An integer passed as a string representing the frequency
		 *                          of RSS updates within the update period. Default '1'.
		 */
		echo apply_filters( 'rss_update_frequency', $frequency );
	?></sy:updateFrequency>
	<?php
	/**
	 * Fires at the end of the RSS2 Feed Header.
	 *
	 * @since 2.0.0
	 */
	do_action('rss2_head');

	query_posts('&posts_per_page=20');
    //$ng_filter = new NGFilter();
    while( have_posts()) : the_post();
/*    if (mb_strlen(get_the_content()) > 200
        && !($ng_filter->judgement(get_the_content()) || $ng_filter->judgement(get_the_title_rss()))) {
*/
    if (mb_strlen(get_the_content()) > 0) {
	?>
	<item>
		<title><?php the_title_rss() ?></title>
		<link><?php the_permalink_rss() ?></link>
		<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
		<dc:creator><![CDATA[<?php the_author() ?>]]></dc:creator>
		<?php the_category_rss('rss2') ?>

		<guid isPermaLink="false"><?php the_guid(); ?></guid>

<?php
$image_id = get_post_thumbnail_id();
$image_url = wp_get_attachment_image_src($image_id,'thumbnail', true);
?>
<?php if (has_post_thumbnail()) {$eyecatchimage=$image_url[0];//アイキャッチの指定 ?>
		<media:thumbnail><?php echo $image_url[0]; ?></media:thumbnail>
<?php } else {$eyecatchimage=get_template_directory_uri(); ?>/images/noimage.png;//アイキャッチ画像が無ければ ?>
		<media:thumbnail><?php echo plugins_url( 'images/noimage.png', dirname(__FILE__)); ?></media:thumbnail>
<?php } ?>


		<description><![CDATA[<?php echo lockets_remove_script_tag(get_the_excerpt()); ?>]]></description>
	<?php $content = get_the_content_feed('rss2'); ?>

		<content:encoded><![CDATA[<?php echo lockets_remove_script_tag($content); ?>]]></content:encoded>

        <georss:where>
            <entry>
                <?php
                $feedcontent = lockets_remove_script_tag($content);
                        if( preg_match("/<georss:point>.*<\/georss:featurename>/", $feedcontent, $matches) ){
                                    print $matches[0];
                                }
                ?>

            </entry>
        </georss:where>

<snf:analytics><![CDATA[
 <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-36543639-6', 'auto');
  ga('send', 'pageview');

</script>
]]></snf:analytics>
<snf:advertisement>
<snf:adcontent>
<![CDATA[ 
<!-- ad -->
]]>
</snf:adcontent>
</snf:advertisement>
<?php rss_enclosure(); ?>
	<?php
	/**
	 * Fires at the end of each RSS2 feed item.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_item' );
	?>
	</item>
	<?php }
    endwhile; ?>
</channel>
</rss>
