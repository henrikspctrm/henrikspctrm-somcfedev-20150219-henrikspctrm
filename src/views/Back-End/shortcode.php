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
             <h4><?php _e('Select the subpage dislaying options if subpage exists for that particular parent page.','subpage');?></h4>
          
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
 *  It is used to render the output when the shortcode is called.	 

 */
function somcfedev_shortcode($atts)
{
	global $post;
	extract(shortcode_atts(array(
					'title' => '',
				), $atts));

	
	$title = empty($title) ? 'Pages' : $title;
	 
	$depth=empty($depth) ? '0' : $depth;

	 
	$somcfedev_str = '';
	$somcfedev_str .= '<div class="somcfedev_container">';
	$somcfedev_str .= '<h3 class="widget_title">'.$title.'</h3>' ;
	 
	// WIDGET CODE GOES HERE
	 
	$page_id= $post->ID;
	
	$args = array(
			'post_parent' => $page_id,
			'post_status' => 'publish',
			'post_type' => 'page',
	);
	
	$attachments = get_children( $args );
	
   	$somcfedev_str .= '<ul class="somcfedev_page_list">';
   	if($attachments)
   	{
		foreach($attachments as $attachment)
   	    	{
   	    		$somcfedev_str .= '<li><a href="'.$attachment->guid.'">'.$attachment->post_title.'</a></li>';
   	    	}
   	}
   	else 
   	{	$args = array(
   				'depth'=> $depth,
   	    		'title_li' => '',
   				'echo' => 0,
   				'post_type'    => 'page',
   				'post_status'  => 'publish',
   			);
   				$pages = wp_list_pages($args);
   				
   				$somcfedev_str .= $pages;
   	}
   	
   	$somcfedev_str .= '</ul>';
	$somcfedev_str .= '</div>';
	
	return $somcfedev_str;
   }
?>
