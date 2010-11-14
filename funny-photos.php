<?php
/*
Plugin Name: Funny photos
Plugin URI: http://www.onlinerel.com/wordpress-plugins/
Description: Plugin "Funny Photos" displays Best photos of the day and Funny photos on your blog. There are over 5,000 photos.
Version: 1.0
Author: A.Kilius
Author URI: http://www.onlinerel.com/wordpress-plugins/
License: GPL2
*/
define(Funny_photos_READER_URL_RSS_DEFAULT, 'http://fun.onlinerel.com/category/best-photos/feed/rss/');
define(Funny_photos_READER_TITLE, 'Funny photos');
define(Funny_photos_MAX_SHOWN_ITEMS, 3);


function Funny_photos_widget_ShowRss($args)
{
 //	$magpierss = ABSPATH.'wp-content/plugins/funny-photos/rss.php';
 require_once('rss.php');
	$options = get_option('Funny_photos_widget');

	if( $options == false ) {
		$options[ 'Funny_photos_widget_url_title' ] = Funny_photos_READER_TITLE;
		$options[ 'Funny_photos_widget_RSS_url' ] = Funny_photos_READER_URL_RSS_DEFAULT;
		$options[ 'Funny_photos_widget_RSS_count_items' ] = Funny_photos_MAX_SHOWN_ITEMS;
	}

 $RSSurl = Funny_photos_READER_URL_RSS_DEFAULT;
	$messages = fetch_rss($RSSurl);
	$title = $options[ 'Funny_photos_widget_url_title' ];
	
	$messages_count = count($messages->items);
	if($messages_count != 0){
		$output = '<ul>';	
	for($i=0; $i<$options['Funny_photos_widget_RSS_count_items'] && $i<$messages_count; $i++)
		{		
  	$foto = '<img src="'.$messages->items[$i]['enclosure'][0]['url'].'" width="160" border="0"   title="'.$messages->items[$i]['description'].'"/>';
 $output .= '<li>';
 $output .= '<a target ="_blank" href="'.$messages->items[$i]['link'].'">'.$foto.'</a></span>';	
	 $output .= '</li>';
	}
		$output .= '</ul> ';
	}
	
	extract($args);	
	?>
	<?php echo $before_widget; ?>
	<?php echo $before_title . $title . $after_title; ?>	
	<?php echo $output; ?>
	<?php echo $after_widget; ?>
	<?php	
}

function Funny_photos_widget_Admin()
{
	$options = $newoptions = get_option('Funny_photos_widget');	
	//default settings
	if( $options == false ) {
		$newoptions[ 'Funny_photos_widget_url_title' ] = Funny_photos_READER_TITLE;
		$newoptions[ 'Funny_photos_widget_RSS_url' ] = Funny_photos_READER_URL_RSS_DEFAULT;
		$newoptions['Funny_photos_widget_RSS_count_items'] = Funny_photos_MAX_SHOWN_ITEMS;		
	}
	if ( $_POST["Funny_photos_widget-submit"] ) {
		$newoptions['Funny_photos_widget_url_title'] = strip_tags(stripslashes($_POST["Funny_photos_widget_url_title"]));
		$newoptions['Funny_photos_widget_RSS_url'] = Funny_photos_READER_URL_RSS_DEFAULT;
		$newoptions['Funny_photos_widget_RSS_count_items'] = strip_tags(stripslashes($_POST["Funny_photos_widget_RSS_count_items"]));
	}	
		
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('Funny_photos_widget', $options);		
	}
	$Funny_photos_widget_url_title = wp_specialchars($options['Funny_photos_widget_url_title']);
	$Funny_photos_widget_RSS_url = Funny_photos_READER_URL_RSS_DEFAULT;	
	$Funny_photos_widget_RSS_count_items = $options['Funny_photos_widget_RSS_count_items'];
	
	?><form method="post" action="">	

	<p><label for="Funny_photos_widget_url_title"><?php _e('Title:'); ?> <input style="width: 350px;" id="Funny_photos_widget_url_title" name="Funny_photos_widget_url_title" type="text" value="<?php echo $Funny_photos_widget_url_title; ?>" /></label></p>
 
	<p><label for="Funny_photos_widget_RSS_count_items"><?php _e('Count Items To Show:'); ?> <input  id="Funny_photos_widget_RSS_count_items" name="Funny_photos_widget_RSS_count_items" size="2" maxlength="2" type="text" value="<?php echo $Funny_photos_widget_RSS_count_items?>" /></label></p>
	
	<br clear='all'></p>
	<input type="hidden" id="Funny_photos_widget-submit" name="Funny_photos_widget-submit" value="1" />	
	</form>
	<?php
}

add_action('admin_menu', 'Funny_photos_menu');

function Funny_photos_menu() {
	add_options_page('Funny photos', 'Funny photos', 8, __FILE__, 'Funny_photos_options');
}

function Funny_photos_options() {	
	?>
	<div class="wrap">

		<h2>Funny photos</h2>
<p><b>Plugin "Funny Photos" displays Best photos of the day and Funny photos on your blog. There are over 5,000 photos.
Add Funny Photos to your sidebar on your blog using  a widget.</b> </p>
<p> <h3>Add the widget "Funny photos"  to your sidebar from Appearance->Widgets and configure the widget options.</h3></p>
 <hr /> <hr />
 <h2>Funny video online</h2>
<p><b>Plugin "Funny video online" displays Funny video on your blog. There are over 10,000 video clips.
Add Funny YouTube videos to your sidebar on your blog using  a widget.</b> </p>
 <h3>Get plugin <a target="_blank" href="http://wordpress.org/extend/plugins/funny-video-online/">Funny video online</h3></a> 
 <hr /> <hr />
   		<h2>Joke of the Day</h2>
<p><b>Plugin "Joke of the Day" displays categorized jokes on your blog. There are over 40,000 jokes in 40 categories. Jokes are saved on our database, so you don't need to have space for all that information. </b> </p>
 <h3>Get plugin <a target="_blank" href="http://wordpress.org/extend/plugins/joke-of-the-day/">Joke of the Day</h3></a>
   <hr /> <hr />
 <h2>Real Estate Finder</h2>
<p><b>Plugin "Real Estate Finder" gives visitors the opportunity to use a large database of real estate.
Real estate search for U.S., Canada, UK, Australia</b> </p>
<h3>Get plugin <a target="_blank" href="http://wordpress.org/extend/plugins/real-estate-finder/">Real Estate Finder</h3></a>
 <hr /> <hr />

 <h2>Jobs Finder</h2>
<p><b>Plugin "Jobs Finder" gives visitors the opportunity to more than 1 million offer of employment.
Jobs search for U.S., Canada, UK, Australia</b> </p>
<h3>Get plugin <a target="_blank" href="http://wordpress.org/extend/plugins/jobs-finder/">Jobs Finder</h3></a>
 <hr /> <hr />
		<h2>Recipe of the Day</h2>
<p><b>Plugin "Recipe of the Day" displays categorized recipes on your blog. There are over 20,000 recipes in 40 categories. Recipes are saved on our database, so you don't need to have space for all that information.</b> </p>
<h3>Get plugin <a target="_blank" href="http://wordpress.org/extend/plugins/recipe-of-the-day/">Recipe of the Day</h3></a>
 <hr /> <hr />
 <h2>WP Social Bookmarking</h2>
<p><b>WP-Social-Bookmarking plugin will add a image below your posts, allowing your visitors to share your posts with their friends, on FaceBook, Twitter, Myspace, Friendfeed, Technorati, del.icio.us, Digg, Google, Yahoo Buzz, StumbleUpon.</b></p>
<p><b>Plugin suport sharing your posts feed on <a href="http://www.onlinerel.com/">OnlineRel</a>. This helps to promote your blog and get more traffic.</b></p>
<p>Advertise your real estate, cars, items... Buy, Sell, Rent. Free promote your site:
<ul>
	<li><a target="_blank" href="http://www.onlinerel.com/">OnlineRel</a></li>
	<li><a target="_blank" href="http://www.easyfreeads.com/">Easy Free Ads</a></li>
	<li><a target="_blank" href="http://www.worldestatesite.com/">World Estate Site</a></li>
</ul>
<h3>Get plugin <a target="_blank" href="http://wordpress.org/extend/plugins/wp-social-bookmarking/">WP Social Bookmarking</h3></a>
</p>
	</div>
	<?php
}

function Funny_photos_widget_Init()
{
  register_sidebar_widget(__('Funny photos'), 'Funny_photos_widget_ShowRss');
  register_widget_control(__('Funny photos'), 'Funny_photos_widget_Admin', 500, 250);
}
add_action("plugins_loaded", "Funny_photos_widget_Init");
?>