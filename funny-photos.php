<?php
/*
Plugin Name: Funny photos
Plugin URI: http://www.premiumresponsive.com/wordpress-plugins/
Description: Plugin "Funny Photos" displays Funny photos on your blog. There are over 5,000 photos.
Version: 2.9
Author: A.Kilius
Author URI: http://www.premiumresponsive.com/wordpress-plugins/
*/

define(Funny_photos_TITLE, 'Funny photos');
define(Funny_photos_URL_RSS_DEFAULT, 'http://www.jokerhub.com/category/best-photos/feed/');
define(Funny_photos_MAX_SHOWN_content, 3);
define(Funny_photos_width_SHOWN_content, 100);
define(Funny_photos_MAX_SHOWN_widg, 3);

function Funny_photos_widget_Init()
{
  register_sidebar_widget(__('Funny photos'), 'Funny_photos_widget_ShowRss');
  register_widget_control(__('Funny photos'), 'Funny_photos_widget_Admin', 500, 250);
}
add_action("plugins_loaded", "Funny_photos_widget_Init");

add_action('admin_menu', 'Funny_photos_menu');
function Funny_photos_menu() {
	add_menu_page('Funny photos', 'Funny photos', 8, __FILE__, 'Funny_photos_options');
}

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
	 $output .= '<!-- WP plugin Funny photos --> <ul>';	
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
 echo $before_widget;  
 echo $before_title . $title . $after_title;  
 echo $output;  
 echo $after_widget;  
}

function Funny_photos_widget_Admin()
{
	$options = $newoptions = get_option('Funny_photos_widget');	
	//default settings
	if( $options == false ) {
		$newoptions[ 'Funny_photos_widget_url_title' ] = Funny_photos_TITLE;
		$newoptions['Funny_photos_widget_RSS_count_widg'] = Funny_photos_MAX_SHOWN_widg;		
		 $newoptions['Funny_photos_widget_RSS_count_content'] = Funny_photos_MAX_SHOWN_content;
		 $newoptions['Funny_photos_width_SHOWN_content'] = Funny_photos_width_SHOWN_content;
	}
	if ( $_POST["Funny_photos_widget_RSS_count_widg"] ) {
		$newoptions['Funny_photos_widget_url_title'] = strip_tags(stripslashes($_POST["Funny_photos_widget_url_title"]));
		$newoptions['Funny_photos_widget_RSS_count_widg'] = strip_tags(stripslashes($_POST["Funny_photos_widget_RSS_count_widg"]));
	}	
		
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('Funny_photos_widget', $options);		
	}
	$Funny_photos_widget_url_title = wp_specialchars($options['Funny_photos_widget_url_title']);
	$Funny_photos_widget_RSS_count_widg = $options['Funny_photos_widget_RSS_count_widg'];
?>	
	<p><label for="Funny_photos_widget_url_title"><?php _e('Title:'); ?> <input style="width: 350px;" id="Funny_photos_widget_url_title" name="Funny_photos_widget_url_title" type="text" value="<?php echo $Funny_photos_widget_url_title; ?>" /></label></p> 
	<p><label for="Funny_photos_widget_RSS_count_widg"><?php _e('Count Items To Show:'); ?>
	<input  id="Funny_photos_widget_RSS_count_widg" name="Funny_photos_widget_RSS_count_widg" size="2" maxlength="2" type="text" value="<?php echo $Funny_photos_widget_RSS_count_widg?>" />
	</label></p>	
	<br clear='all'>
	<?php
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
		$options['Funny_photos_width_SHOWN_content'] = Funny_photos_width_SHOWN_content;
	}
if($options['Funny_photos_widget_RSS_count_content'] !=0){
$pldir = WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)); 
$images = $pldir.'/images/';
	$content .= '<div style="clear:both; margin:2px;"></div>';
 $feed = Funny_photos_URL_RSS_DEFAULT;
 	$title = $options[ 'Funny_photos_widget_url_title' ];
	$width = $options['Funny_photos_width_SHOWN_content'];
$rss = fetch_feed( $feed );
		if ( !is_wp_error( $rss ) ) :
			$maxitems = $rss->get_item_quantity($options['Funny_photos_widget_RSS_count_content'] );
			$items = $rss->get_items( 0, $maxitems );
				endif;
	if($items) { 
 	foreach ( $items as $item ) :
  $titlee = trim($item->get_title()); 
  if ($enclosure = $item->get_enclosure())
	{ 
$content .= '<a href="';
$content .=  $item->get_permalink();
 $content .= '"  title="'.$titlee.'" target="_blank">';
  $content .= '<img src="'.$enclosure->link.'"  width="'.$width.'" alt="'.$titlee.'"  title="'.$titlee.'" /></a> ';
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
		$newoptions['Funny_photos_width_SHOWN_content'] = Funny_photos_width_SHOWN_content;
	}
	if ( $_POST["b_update"] ) {
		$newoptions['Funny_photos_widget_RSS_count_content'] = strip_tags(stripslashes($_POST["Funny_photos_widget_RSS_count_content"]));
		$newoptions[ 'Funny_photos_width_SHOWN_content' ]  = strip_tags(stripslashes($_POST[ 'Funny_photos_width_SHOWN_content']));
			}	

	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('Funny_photos_widget', $options);		
	}
	$Funny_photos_widget_RSS_count_content = $options['Funny_photos_widget_RSS_count_content'];
	$Funny_photos_width_SHOWN_content = $options['Funny_photos_width_SHOWN_content'];
	echo "<div class='updated fade'><p><strong>Options saved</strong></p></div>";
 	?>
	<div class="wrap">
	<h2>Funny photos Settings </h2>
	<form method="post" action="#">	 
	<p><label for="Funny_photos_widget_RSS_count_content"><?php _e('Count boxes To Show after the posts:'); ?> (1-9) <input  id="Funny_photos_widget_RSS_count_content" name="Funny_photos_widget_RSS_count_content" size="2" maxlength="1" type="text" value="<?php echo $Funny_photos_widget_RSS_count_content;?>" />				
	 </label>
	 <label for="Funny_photos_width_SHOWN_content"><?php _e('Foto width:'); ?> (default 100px) <input  id="Funny_photos_width_SHOWN_content" name="Funny_photos_width_SHOWN_content" size="3" maxlength="3" type="text" value="<?php echo $Funny_photos_width_SHOWN_content;?>" />				
	 </label>
	 <input type="submit" name="b_update" class="button-primary" value="  Save Changes  " /></p>
	 	</form> 
		Set  <strong>0 </strong> to disable boxes after the posts.                                                                      
<hr />                                        
<p> <strong>Plugin "Funny Photos" displays Best photos of the day and Funny photos on your blog. There are over 5,000 photos.
Add Funny Photos to your sidebar on your blog using  a widget. </strong> </p>
<p> <h3>Add the widget "Funny photos"  to your sidebar from  <a href="<? echo "./widgets.php";?>"> Appearance->Widgets</a>  and configure the widget options.</h3> </p>
<p> 
<h3>More <a href="http://www.premiumresponsive.com/wordpress-plugins/" target="_blank"> WordPress Plugins</a></h3>
</p>
  	</div>
	<?php
}
?>