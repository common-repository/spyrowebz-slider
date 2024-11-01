<?php

/*
Plugin Name: Spyrowebz Slider
Plugin URI: http://wordpress.org/extend/plugins/spyrowebz-slider/
Description: A plugin which Create a Slider with fade animation and also display title and descriptions of slider.
Version: 1.0
Author: Spyroweb
Author URI: https://www.elance.com/s/edit/spyroweb/
License: GPLv2
*/

register_activation_hook(__FILE__, 'spyro_slider_activate');

function spyro_slider_activate() {
    spyro_slider_register();
    flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, 'spyro_slider_deactivate');

function spyro_slider_deactivate() {
    flush_rewrite_rules();
}

// Register Spyrowebs Gallery post type
add_action( 'init', 'spyro_slider_register' );
    function spyro_slider_register() {
    register_post_type( 'Spyrowebz slider',
        array(
            'labels' => array(
                'name' => 'Spyrowebz Slider',
                'singular_name' => 'Spyro Slider',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Spyro slider',
                'edit' => 'Edit',
                'edit_item' => 'Edit Spyro Slider',
                'new_item' => 'New Spyro Slider',
                'view' => 'View',
                'view_item' => 'View Spyro Slider',
                'search_items' => 'Search Spyro Slider',
                'not_found' => 'No Spyro Slider found',
                'not_found_in_trash' => 'No Spyro slider found in Trash',
                'parent' => 'Parent Spyro Slider'
            ),
 
            'public' => true,
            'menu_position' => 5,
            'supports' => array( 'title', 'editor', 'thumbnail' ),
            'menu_icon'            =>  'dashicons-format-gallery',
            'has_archive' => true,
            'register_meta_box_cb' => 'add_spyro_metaboxes'
        )
    );
}
function plugin_add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=spyrowebz_slider">Settings</a>';
  	array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );
// Add the Events Meta Boxes
function add_spyro_metaboxes() {
    add_meta_box('wpt_spyro_location', 'Slide Url', 'wpt_spyro_location', 'spyrowebzslider', 'side', 'default');
}
// The Event Location Metabox
function wpt_spyro_location() {
	global $post;
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	// Get the location data if its already been entered
	$location = get_post_meta($post->ID, '_location', true);
	// Echo out the field
	echo '<input type="text" name="_location" value="' . $location  . '" class="widefat" />';
}
// Save the Metabox Data
function wpt_save_spyro_meta($post_id, $post) {
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	$events_meta['_location'] = $_POST['_location'];
	// Add values of $events_meta as custom fields
	foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
}
add_action('save_post', 'wpt_save_spyro_meta', 1, 2); // save the custom fields
// Register custom JS scripts


add_shortcode('spyrowebz_slider', 'spyrowebz_slider');

function spyrowebz_slider() {
  $gquery = new WP_Query( array(
        'posts_per_page' => -1,
        'post_type' => 'spyrowebzslider',
    ) );
    
   $options = get_option( 'spyro_slider_settings' );
    $output.='<div class="ruled1" style="height: '. $options['spyro_slider_text_field_0'] . 'px; width:'. $options['spyro_slider_text_field_1'] . 'px; overflow: hidden;"><div id="spyro-container1"><div class="ws_images"><ul>';
    if ( $gquery->have_posts() ) {
        while ( $gquery->have_posts() ) {
            $gquery->the_post();
            
            $url = get_post_meta( get_the_ID(), '_location', true );
             if($options ["spyro_slider_select_field_5"]==2){ 
                 $output.='<li><a href="'. $url .'"><img src="' .  wp_get_attachment_url( get_post_thumbnail_id($post->ID) ) . '" alt="'.get_the_title($post->ID).'" title="" id="wows1_'.$count.'" />'; 
                 if($options ["spyro_slider_select_field_6"]==1){
                    $output.='<a href="'. $url .'"> '.get_the_content($post->ID).'</a>';
                    }
                 if($options ["spyro_slider_select_field_6"]==2){
                       $output.='';
                    }
                 $output.='</a></li>';           
                }
            else {
                
                 $output.='<li><a href="'. $url .'"><img src="' .  wp_get_attachment_url( get_post_thumbnail_id($post->ID) ) . '" alt="'.get_the_title($post->ID).'" title="'.get_the_title($post->ID).'" id="wows1_'.$count.'" />'; 
                 if($options ["spyro_slider_select_field_6"]==1){
                    $output.='<a href="'. $url .'"> '.get_the_content($post->ID).'</a>';
                    }
                 if($options ["spyro_slider_select_field_6"]==2){
                       $output.='';
                    }
                 $output.='</a></li>';  
                
                }
             if($options ["spyro_slider_select_field_7"]==2){?>
                     <style>
                     #spyro-container1 a.ws_next, #spyro-container1 a.ws_prev{
                        display: none;
                     }
                     
                     </style>
                       
                   <?php }
                   if($options ["spyro_slider_select_field_8"]==2){
                    
                    $autoplay==0;
                    
                     }
                    else{
                        $autoplay=1;
                         $output.='<div id="autoplay" style="display:none"> '.$autoplay.'</div>';
                    } 
                    
                    if($options ["spyro_slider_select_field_9"]==2){
                    
                    $stop==0;
                    
                     }
                    else{
                        $stop=1;
                         $output.='<div id="stop" style="display:none"> '.$stop.'</div>';
                    } 
                    $count++;
                }
             } 
           $output.='</ul></div>';
           
           if($options ["spyro_slider_select_field_2"]==1){
           $output.='<div class="ws_bullets"><div>';
           if ( $gquery->have_posts() ) {
             while ( $gquery->have_posts() ) {
            $gquery->the_post();
            
            $output.='<a href="#" title="'.get_the_title($post->ID).'"><img src="' .  wp_get_attachment_url( get_post_thumbnail_id($post->ID) ) . '" width="90" height="90" alt="'.get_the_title($post->ID).'"/>'.get_the_title($post->ID).'</a>';
            }
            }
            }
            
            $output.='</div></div><div class="ws_shadow"></div> </div></div>';
        
 return $output;   
}

add_action('wp_footer', 'spyrowebs_slider_enqueue_scripts');
function spyrowebs_slider_enqueue_scripts() {
    wp_register_script('spyro', plugins_url('js/spyrowebz.js', __FILE__ ));
    wp_register_script('slider', plugins_url('js/script.js', __FILE__ ));  
    wp_enqueue_script('spyro');
    wp_enqueue_script('slider');
  
}
add_action('init', 'spyrowebs_slider_enqueue_styles');
function spyrowebs_slider_enqueue_styles() {
    wp_enqueue_style('spyroslider', plugins_url('css/styles.css', __FILE__ ));
}

add_action( 'admin_menu', 'spyro_slider_add_admin_menu' );
add_action( 'admin_init', 'spyro_slider_settings_init' );


function spyro_slider_add_admin_menu(  ) { 

	add_menu_page( 'Spyrowebz slider', 'Spyrowebz slider', 'manage_options', 'spyrowebz_slider', 'spyrowebz_slider_options_page' );

}


function spyro_slider_settings_exist(  ) { 

	if( false == get_option( 'spyrowebz_slider_settings' ) ) { 

		add_option( 'spyrowebz_slider_settings' );

	}

}


function spyro_slider_settings_init(  ) { 

	register_setting( 'pluginPage', 'spyro_slider_settings' );

	add_settings_section(
		'spyro_slider_pluginPage_section', 
		__( '', 'spyro_slider' ), 
		'spyro_slider_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'spyro_slider_text_field_0', 
		__( 'Slider Height', 'spyro_slider' ), 
		'spyro_slider_text_field_0_render', 
		'pluginPage', 
		'spyro_slider_pluginPage_section' 
	);

	add_settings_field( 
		'spyro_slider_text_field_1', 
		__( 'Slider Width', 'spyro_slider' ), 
		'spyro_slider_text_field_1_render', 
		'pluginPage', 
		'spyro_slider_pluginPage_section' 
	);

	add_settings_field( 
		'spyro_slider_select_field_2', 
		__( 'Show Thumbnail', 'spyro_slider' ), 
		'spyro_slider_select_field_2_render', 
		'pluginPage', 
		'spyro_slider_pluginPage_section' 
	);
   add_settings_field( 
		'spyro_slider_select_field_5', 
		__( 'Show Title', 'spyro_slider' ), 
		'spyro_slider_select_field_5_render', 
		'pluginPage', 
		'spyro_slider_pluginPage_section' 
	);
     add_settings_field( 
		'spyro_slider_select_field_6', 
		__( 'Show Description', 'spyro_slider' ), 
		'spyro_slider_select_field_6_render', 
		'pluginPage', 
		'spyro_slider_pluginPage_section' 
	);
    add_settings_field( 
		'spyro_slider_select_field_7', 
		__( 'Show Arrows', 'spyro_slider' ), 
		'spyro_slider_select_field_7_render', 
		'pluginPage', 
		'spyro_slider_pluginPage_section' 
	);
    add_settings_field( 
		'spyro_slider_select_field_8', 
		__( 'Auto Play', 'spyro_slider' ), 
		'spyro_slider_select_field_8_render', 
		'pluginPage', 
		'spyro_slider_pluginPage_section' 
	);
    add_settings_field( 
		'spyro_slider_select_field_9', 
		__( 'Stop on Mouse Over', 'spyro_slider' ), 
		'spyro_slider_select_field_9_render', 
		'pluginPage', 
		'spyro_slider_pluginPage_section' 
	);
}

function spyro_slider_text_field_0_render(  ) { 

	$options = get_option( 'spyro_slider_settings' );
	?>
	<input type='text' name='spyro_slider_settings[spyro_slider_text_field_0]' value='<?php echo $options['spyro_slider_text_field_0']; ?>'> px
	<?php
}
function spyro_slider_text_field_1_render(  ) { 

	$options = get_option( 'spyro_slider_settings' );
	?>
	<input type='text' name='spyro_slider_settings[spyro_slider_text_field_1]' value='<?php echo $options['spyro_slider_text_field_1']; ?>'> px
	<?php
}
function spyro_slider_select_field_2_render(  ) { 

	$options = get_option( 'spyro_slider_settings' );
	?>
	<select name='spyro_slider_settings[spyro_slider_select_field_2]'>
		<option value='1' <?php selected( $options['spyro_slider_select_field_2'], 1 ); ?>>Yes</option>
		<option value='2' <?php selected( $options['spyro_slider_select_field_2'], 2 ); ?>>No</option>
	</select>

<?php
}
function spyro_slider_select_field_5_render(  ) { 

	$options = get_option( 'spyro_slider_settings' );
	?>
	<select name='spyro_slider_settings[spyro_slider_select_field_5]'>
		<option value='1' <?php selected( $options['spyro_slider_select_field_5'], 1 ); ?>>Yes</option>
		<option value='2' <?php selected( $options['spyro_slider_select_field_5'], 2 ); ?>>No</option>
	</select>

<?php
}
function spyro_slider_select_field_6_render(  ) { 

	$options = get_option( 'spyro_slider_settings' );
	?>
	<select name='spyro_slider_settings[spyro_slider_select_field_6]'>
		<option value='1' <?php selected( $options['spyro_slider_select_field_6'], 1 ); ?>>Yes</option>
		<option value='2' <?php selected( $options['spyro_slider_select_field_6'], 2 ); ?>>No</option>
	</select>

<?php
}
function spyro_slider_select_field_7_render(  ) { 

	$options = get_option( 'spyro_slider_settings' );
	?>
	<select name='spyro_slider_settings[spyro_slider_select_field_7]'>
		<option value='1' <?php selected( $options['spyro_slider_select_field_7'], 1 ); ?>>Yes</option>
		<option value='2' <?php selected( $options['spyro_slider_select_field_7'], 2 ); ?>>No</option>
	</select>

<?php
}
function spyro_slider_select_field_8_render(  ) { 

	$options = get_option( 'spyro_slider_settings' );
	?>
	<select name='spyro_slider_settings[spyro_slider_select_field_8]'>
		<option value='1' <?php selected( $options['spyro_slider_select_field_8'], 1 ); ?>>Yes</option>
		<option value='2' <?php selected( $options['spyro_slider_select_field_8'], 2 ); ?>>No</option>
	</select>

<?php
}
function spyro_slider_select_field_9_render(  ) { 

	$options = get_option( 'spyro_slider_settings' );
	?>
	<select name='spyro_slider_settings[spyro_slider_select_field_9]'>
		<option value='1' <?php selected( $options['spyro_slider_select_field_9'], 1 ); ?>>Yes</option>
		<option value='2' <?php selected( $options['spyro_slider_select_field_9'], 2 ); ?>>No</option>
	</select>

<?php
}
function spyro_slider_settings_section_callback(  ) { 

	echo __( 'Note: Set your slide height and width and then use the same size images for slider. If you like our Plugin Please rate us on WordPress <a href="http://wordpress.org/plugins/spyrowebz-slider/">Spyrowebz Slider</a> If you want to hire me please click here <a href="https://www.elance.com/s/edit/spyroweb/">My Profile</a> or You can direct contact me o my skpye sultan_khan1231 Using shotrcode [spyrowebz_slider] into post or page.', 'spyro_slider' );

}
function spyrowebz_slider_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h2>Spyrowebz slider</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php
}
?>
