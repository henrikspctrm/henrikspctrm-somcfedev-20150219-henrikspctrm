<?php
/*
Plugin Name: somc-fedev-20150219-henrikspctrm
Plugin URI: http://informationsmagi.se/henrik/about
Description: widget and shortcode displaying sub pages.
Author: Henrik Dahlström
Version: 0.02
Author URI: http://spctrm.se
*/

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

$somcfedev_plugin_data = get_plugin_data( __FILE__ );
	
define('somcfedevSUBPAGE_VERSION', $somcfedev_plugin_data['Version']);

register_activation_hook( __FILE__, 'somcfedev_default_option_value' );

register_uninstall_hook(__FILE__,'somcfedev_delete_option_value');

function somcfedev_default_option_value()
{
	$default_values=array(
				    'version'=>somcfedevSUBPAGE_VERSION,
			);
	 add_option('somcfedev_subpage_settings',$default_values);
}

function somcfedev_delete_option_value()
{
	 delete_option('somcfedev_subpage_settings',$default_values);
}

add_action('plugins_loaded',array('somcfedev_sub_pages','somcfedev_myplugin_init'));
add_action( 'widgets_init', 'somcfedev_sub_pages',1 );

function somcfedev_sub_pages() {
	register_widget( 'somcfedev_sub_pages' );
}

function levelFunction($p_id, $page){
	$children = get_pages("child_of=$p_id&sort_column=post_title");
	$immediate_children = get_pages("child_of=$p_id&parent=$p_id&sort_column=post_title");
	if($children) {
		$titletruncated = (strlen($page->post_title) > 13) ? substr($page->post_title,0,20).'...' : $page->post_title;
		print '<li class="subp">'  . get_the_post_thumbnail($page->ID,array( 20, 20)) . $titletruncated  .  '<span class="icon-sort"></span>';
		print '<ul>';
		foreach($immediate_children as $child) {
			levelFunction($child->ID, $child);
		}
		print '</ul></li>';
	}
	else {
		 $titletruncated = (strlen($page->post_title) > 13) ? substr($page->post_title,0,20).'...' : $page->post_title;
         print '<li class="subp singlesp">' . get_the_post_thumbnail($page->ID,array( 20, 20)) . $titletruncated  . '</li>';
	}
}

class somcfedev_sub_pages extends WP_Widget
{
	public function __construct()
	{
		$widget_ops = array('classname' => 'somcfedev_sub_pages','description' => __('Subpages displayed for the page','subpage')
				);
		$this->WP_Widget('somcfedev_sub_pages', __('somc-fedev-20150219-henrikspctrm','subpage'), $widget_ops);
		add_shortcode('sub_page','somcfedev_shortcode');
	}

    public function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = $instance['title']; ?>

		<!-- Title of widget-->
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'subpage'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>

<?php }
 function update($new_instance, $old_instance)
  {	
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];

    return $instance;
  }

function widget($args, $instance)
 {
 	global $post;
  	extract($args,EXTR_SKIP);
    echo $before_widget;
    $title = empty($instance['title']) ? 'Pages' : apply_filters('widget_title', $instance['title']);
    if (!empty($title))
      echo $before_title . $title . $after_title;
    // WIDGET
      $page_id= $post->ID;
	 //get currentpage as top
	 $top_level_pages = get_pages("parent=$page_id&sort_column=menu_order");
	 	if($top_level_pages)
	 	{
			print '<ul class="subpageswidget">';
	 	foreach($top_level_pages as $page) {
	 	$p_id = $page->ID;
	 	levelFunction($p_id, $page);
	 	}
			print '</ul>';
	 }
	 echo $after_widget;
 }
	 /**
 *  plugin directory path.
*/
public function somcfedev_get_plugin_url()
{	
   	return plugin_dir_path(__FILE__);
}
/**
 *  plugin folders path.

 */
 public static function somcfedev_get_plugin_dir_url()
 {
  		return plugins_url('', __FILE__);
 }
 
 public static function somcfedev_myplugin_init() {
	 wp_enqueue_style( 'subpcss', self::somcfedev_get_plugin_dir_url().'/views/css/subp.css' );
	 wp_enqueue_script('subpjs', self::somcfedev_get_plugin_dir_url(). '/views/js/subp.js', array('jquery'), '1.0', true) ;
 }
}

$sub_page_object=new somcfedev_sub_pages();

include_once($sub_page_object->somcfedev_get_plugin_url().'/views/Back-End/shortcode.php');
?>