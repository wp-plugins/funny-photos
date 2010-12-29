<?php
/*
Plugin Name: Funny photos
Version: 1.4.1
Plugin URI: http://www.onlinerel.com/wordpress-plugins/
Description: Plugin "Funny Photos" displays Best photos of the day and Funny photos on your blog. There are over 5,000 photos.
Author: A.Kilius
Author URI: http://www.onlinerel.com/wordpress-plugins/
*/

define(Funny_photos_URL_RSS_DEFAULT, 'http://fun.onlinerel.com/category/best-photos/feed/rss/');
define(Funny_photos_TITLE, 'Funny photos');
define(Funny_photos_MAX_SHOWN_widg, 3);
define(Funny_photos_MAX_SHOWN_content, 3);

function Funny_photos_widget_ShowRss($args)
{
	$options = get_option('Funny_photos_widget');
	if( $options == false ) {
		$options[ 'Funny_photos_widget_url_title' ] = Funny_photos_TITLE;
		$options[ 'Funny_photos_widget_RSS_count_widg' ] = Funny_photos_MAX_SHOWN_widg;
	}

 $feed = Funny_photos_URL_RSS_DEFAULT;

	$title = $options[ 'Funny_photos_widget_url_title' ];
	$rss = fetch_feed( $feed );
		if ( !is_wp_error( $rss ) ) :
			$maxitems = $rss->get_item_quantity($options['Funny_photos_widget_RSS_count_widg'] );
			$items = $rss->get_items( 4, $maxitems );
				endif;
	 $output .= '<ul>';	
	if($items) { 
 			foreach ( $items as $item ) :
				// Create post object
  $titlee = trim($item->get_title()); 
  if ($enclosure = $item->get_enclosure())
	{ 
 $output .= '<li> <a href="';
 $output .=  $item->get_permalink();
  $output .= '"  title="'.$titlee.'" target="_blank">';
   $output .= '<img src="'.$enclosure->link.'"  alt="'.$titlee.'"  title="'.$titlee.'" /></a> ';
	 $output .= '</li>'; 
	}
	  		endforeach;		
	}
			$output .= '</ul> ';	 

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
		$newoptions[ 'Funny_photos_widget_url_title' ] = Funny_photos_TITLE;
		$newoptions['Funny_photos_widget_RSS_count_widg'] = Funny_photos_MAX_SHOWN_widg;		
		 $newoptions['Funny_photos_widget_RSS_count_content'] = Funny_photos_MAX_SHOWN_content;
	}
	if ( $_POST["Funny_photos_widget-submit"] ) {
		$newoptions['Funny_photos_widget_url_title'] = strip_tags(stripslashes($_POST["Funny_photos_widget_url_title"]));
		$newoptions['Funny_photos_widget_RSS_count_widg'] = strip_tags(stripslashes($_POST["Funny_photos_widget_RSS_count_widg"]));
		$newoptions[ 'Funny_photos_widget_RSS_count_content' ]  = $newoptions[ 'Funny_photos_widget_RSS_count_content'];
	}	
		
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('Funny_photos_widget', $options);		
	}
	$Funny_photos_widget_url_title = wp_specialchars($options['Funny_photos_widget_url_title']);
	$Funny_photos_widget_RSS_count_widg = $options['Funny_photos_widget_RSS_count_widg'];
	
	?><form method="post" action="">	

	<p><label for="Funny_photos_widget_url_title"><?php _e('Title:'); ?> <input style="width: 350px;" id="Funny_photos_widget_url_title" name="Funny_photos_widget_url_title" type="text" value="<?php echo $Funny_photos_widget_url_title; ?>" /></label></p>
 
	<p><label for="Funny_photos_widget_RSS_count_widg"><?php _e('Count Items To Show:'); ?> <input  id="Funny_photos_widget_RSS_count_widg" name="Funny_photos_widget_RSS_count_widg" size="2" maxlength="2" type="text" value="<?php echo $Funny_photos_widget_RSS_count_widg?>" /></label></p>
	
	<br clear='all'></p>
	<input type="hidden" id="Funny_photos_widget-submit" name="Funny_photos_widget-submit" value="1" />	
	</form>
	<?php
}

add_action('admin_menu', 'Funny_photos_menu');

function Funny_photos_menu() {
	add_options_page('Funny photos', 'Funny photos', 8, __FILE__, 'Funny_photos_options');
}
	
	add_filter("plugin_action_links", 'Funny_photos_ActionLink', 10, 2);
function Funny_photos_ActionLink( $links, $file ) {
	    static $this_plugin;		
		if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__); 
        if ( $file == $this_plugin ) {
			$settings_link = "<a href='".admin_url( "options-general.php?page=".$this_plugin )."'>". __('Settings') ."</a>";
			array_unshift( $links, $settings_link );
		}
		return $links;
	}


add_filter('the_content', 'Funny_photos_content', 48);

function Funny_photos_content($content) {
	if ( is_single() && !is_home() && !is_front_page() && !is_page() && !is_front_page() && !is_archive()) {

	$options = get_option('Funny_photos_widget');
	if( $options == false ) {
		$options[ 'Funny_photos_widget_url_title' ] = Funny_photos_TITLE;
		$options[ 'Funny_photos_widget_RSS_count_widg' ] = Funny_photos_MAX_SHOWN_widg;
		 $options[ 'Funny_photos_widget_RSS_count_content' ] = Funny_photos_MAX_SHOWN_content;
	}
if($options['Funny_photos_widget_RSS_count_content'] !=0){
$pldir = WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)); 
$images = $pldir.'/images/';
	$content .= '<div style="clear:both; margin:3px;"></div>';
 $feed = Funny_photos_URL_RSS_DEFAULT;
 	$title = $options[ 'Funny_photos_widget_url_title' ];
$rss = fetch_feed( $feed );
		if ( !is_wp_error( $rss ) ) :
			$maxitems = $rss->get_item_quantity($options['Funny_photos_widget_RSS_count_content'] );
			$items = $rss->get_items( 0, $maxitems );
				endif;
	if($items) { 
 			foreach ( $items as $item ) :
				// Create post object
  $titlee = trim($item->get_title()); 
  if ($enclosure = $item->get_enclosure())
	{ 
$content .= '<a href="';
$content .=  $item->get_permalink();
 $content .= '"  title="'.$titlee.'" target="_blank">';
  $content .= '<img src="'.$enclosure->link.'"  alt="'.$titlee.'"  title="'.$titlee.'" /></a> ';
	$content .= ' &nbsp; '; 
	}
	  		endforeach;		
	}
	  $content .= '<div style="clear:both; margin:3px;"></div>';
}
    }		
	
	return $content;
}


function Funny_photos_options() {	
	?>
	<div class="wrap">
<?
		$options = $newoptions = get_option('Funny_photos_widget');	
	//default settings
	if( $options == false ) {
		$newoptions[ 'Funny_photos_widget_url_title' ] = Funny_photos_TITLE;
		$newoptions['Funny_photos_widget_RSS_count_widg'] = Funny_photos_MAX_SHOWN_widg;		
		$newoptions['Funny_photos_widget_RSS_count_content'] = Funny_photos_MAX_SHOWN_content;		
	}
	if ( $_POST["b_update"] ) {
		$newoptions['Funny_photos_widget_url_title'] = $newoptions[ 'Funny_photos_widget_url_title' ] ;
		$newoptions['Funny_photos_widget_RSS_count_widg'] = $newoptions['Funny_photos_widget_RSS_count_widg'];
		$newoptions['Funny_photos_widget_RSS_count_content'] = strip_tags(stripslashes($_POST["Funny_photos_widget_RSS_count_content"]));
	}	
		
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('Funny_photos_widget', $options);		
	}

	$Funny_photos_widget_RSS_count_content = $options['Funny_photos_widget_RSS_count_content'];

	echo "<div class='updated fade'><p><strong>Options saved</strong></p></div>";
 
	?>
	<div class="wrap">
	<h2>Funny photos Settings </h2>

	<form method="post" action="#">	 
	<p><label for="Funny_photos_widget_RSS_count_content"><?php _e('Count boxes To Show after the posts:'); ?> (1-9) <input  id="Funny_photos_widget_RSS_count_content" name="Funny_photos_widget_RSS_count_content" size="2" maxlength="1" type="text" value="<?php echo $Funny_photos_widget_RSS_count_content?>" />
				<input type="submit" name="b_update" class="button-primary" value="  Save Changes  " />
	 </label></p>
	 	</form> 
		Set <b>0</b> to disable boxes after the posts.
<hr />
<p><b>Plugin "Funny Photos" displays Best photos of the day and Funny photos on your blog. There are over 5,000 photos.
Add Funny Photos to your sidebar on your blog using  a widget.</b> </p>
<p> <h3>Add the widget "Funny photos"  to your sidebar from  <a href="<? echo "./widgets.php";?>"> Appearance->Widgets</a>  and configure the widget options.</h3></p>
 <hr /> <hr />
 <h2>Blog Promotion</h2>
<p><b>If you produce original news or entertainment content, you can tap into one of the most technologically advanced traffic exchanges among blogs! Start using our Blog Promotion plugin on your site and receive 150%-300% extra traffic free! 
Idea is simple - the more traffic you send to us, the more we can send you back.</b> </p>
 <h3>Get plugin <a target="_blank" href="http://wordpress.org/extend/plugins/blog-promotion/">Blog Promotion</h3></a> 
 <hr />
 <h2>Funny video online</h2>
<p><b>Plugin "Funny video online" displays Funny video on your blog. There are over 10,000 video clips.
Add Funny YouTube videos to your sidebar on your blog using  a widget.</b> </p>
 <h3>Get plugin <a target="_blank" href="http://wordpress.org/extend/plugins/funny-video-online/">Funny video online</h3></a> 
  <hr />
   		<h2>Joke of the Day</h2>
<p><b>Plugin "Joke of the Day" displays categorized jokes on your blog. There are over 40,000 jokes in 40 categories. Jokes are saved on our database, so you don't need to have space for all that information. </b> </p>
 <h3>Get plugin <a target="_blank" href="http://wordpress.org/extend/plugins/joke-of-the-day/">Joke of the Day</h3></a>
    <hr />
 <h2>Real Estate Finder</h2>
<p><b>Plugin "Real Estate Finder" gives visitors the opportunity to use a large database of real estate.
Real estate search for U.S., Canada, UK, Australia</b> </p>
<h3>Get plugin <a target="_blank" href="http://wordpress.org/extend/plugins/real-estate-finder/">Real Estate Finder</h3></a>
  <hr />

 <h2>Jobs Finder</h2>
<p><b>Plugin "Jobs Finder" gives visitors the opportunity to more than 1 million offer of employment.
Jobs search for U.S., Canada, UK, Australia</b> </p>
<h3>Get plugin <a target="_blank" href="http://wordpress.org/extend/plugins/jobs-finder/">Jobs Finder</h3></a>
  <hr />
		<h2>Recipe of the Day</h2>
<p><b>Plugin "Recipe of the Day" displays categorized recipes on your blog. There are over 20,000 recipes in 40 categories. Recipes are saved on our database, so you don't need to have space for all that information.</b> </p>
<h3>Get plugin <a target="_blank" href="http://wordpress.org/extend/plugins/recipe-of-the-day/">Recipe of the Day</h3></a>
  <hr />
 <h2>WP Social Bookmarking</h2>
<p><b>WP-Social-Bookmarking plugin will add a image below your posts, allowing your visitors to share your posts with their friends, on FaceBook, Twitter, Myspace, Friendfeed, Technorati, del.icio.us, Digg, Google, Yahoo Buzz, StumbleUpon.</b></p>
<p><b>Plugin suport sharing your posts feed on <a href="http://www.easyfreeads.com/">EasyFreeAds</a>. This helps to promote your blog and get more traffic.</b></p>
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