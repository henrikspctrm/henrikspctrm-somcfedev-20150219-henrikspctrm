<?php

add_action( 'admin_menu', 'somcfedev_admin_menu' );
add_action('admin_enqueue_scripts','somcfedev_add_script');

function somcfedev_add_script()
{

	global 	$somcfedev_plugin_version;

	if( !wp_script_is( 'jquery' ) )
	{
		wp_enqueue_script('jquery');
	}
	
	wp_enqueue_script('shortcode_js',somcfedev_sub_pages::somcfedev_get_plugin_dir_url().'/js/jquery_create_shortocode.js',array('jquery'), $somcfedev_plugin_version);
	
}
function levelShortCodeFunction($p_id, $page){
	global $somcfedev_str;
	$children = get_pages("child_of=$p_id&sort_column=post_title");
	$immediate_children = get_pages("child_of=$p_id&parent=$p_id&sort_column=post_title");
	if($children) {
		$titletruncated = (strlen($page->post_title) > 13) ? substr($page->post_title,0,20).'...' : $page->post_title;
		$somcfedev_str .= '<li class="subp">'  . get_the_post_thumbnail($page->ID,array( 20, 20)) . $titletruncated  .  '<span class="icon-sort"></span>';
		$somcfedev_str .= '<ul>';
		foreach($immediate_children as $child) {
			levelShortCodeFunction($child->ID, $child);
		}
		$somcfedev_str .= '</ul></li>';
	}
	else {
		$titletruncated = (strlen($page->post_title) > 13) ? substr($page->post_title,0,20).'...' : $page->post_title;
		$somcfedev_str .= '<li class="subp singlesp">' . get_the_post_thumbnail($page->ID,array( 20, 20)) . $titletruncated  . '</li>';
	}
}
/**
 *  It is used to add the option page for the plugin page.

 */
function somcfedev_admin_menu() {
	add_options_page( 'somc-fedev-20150219-henrikspctrm','somc-fedev-20150219-henrikspctrm','manage_options', 'sub-page', 'somcfedev_options_page' );
}

/**
 *  It is used to render the form for the settings page.

 */
function somcfedev_options_page() {

//$result='0';
 
?>
    <div class="wrap">
        <h2><?php _e('somc-fedev-20150219-henrikspctrm Plugin Options','subpage'); ?></h2>
        
        <form action="" method="POST">
            
             <h3><?php _e('Sub Page Display Options','subpage'); ?></h3>
             <h4><?php _e('Select the subpage dislaying options if subpage exists for the particular current page it is placed on','subpage');?></h4>
          
             <table class="form-table">
             	<tbody>
             		<tr valign="top">
             		<th scope="row"><?php _e('Title','subpage');?></th>
             		<!-- Title Field -->
             		<td>
             		<?php 
				echo "<input id='title' type='text' name='title' value='Pages' />";?>
             		</td>
             		</tr>
             	</tbody>
             </table>

              <h3><?php _e('Dynamic Shortcode','subpage');?></h3>
             <h4> <?php _e('Copy the shortcode and paste it where you want to display subpages','subpage');?></h4>
             <table class="form-table">
             	<tbody>
             		<tr valign="top">
             		<th scope="row"><?php _e('Dynamic Shortcode:','subpage');?></th>
             		<td>
             			<?php echo '<div id= "shortcode"></div>';?>
             		</td>
             </tr></tbody></table>
        </form>
    </div>
    <?php
}




/**
 *  used to render the output when the shortcode is called.

 */
function somcfedev_shortcode($atts)
{
	global $post;
	extract(shortcode_atts(array(
		'title' => '',
	), $atts));

	global $somcfedev_str;
	$title = empty($title) ? 'Pages' : $title;

	 
	$somcfedev_str = '';
	$somcfedev_str .= '<div class="somcfedev_container">';
	$somcfedev_str .= '<h3 class="widget_title">'.$title.'</h3>' ;
	// WIDGET CODE

	$page_id= $post->ID;
	//get currentpage as top
	$top_level_pages = get_pages("parent=$page_id&sort_column=menu_order");
	if($top_level_pages)
	{
		$somcfedev_str .=  '<ul class="subpageswidget">';
		foreach($top_level_pages as $page) {
			$p_id = $page->ID;
			levelShortCodeFunction($p_id, $page);
		}
		$somcfedev_str .=  '</ul>';
	}

	$somcfedev_str .=  '</div>';
	
	return $somcfedev_str;
   }
?>
