<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              mdhrseanli@gmail.com
 * @since             1.0.0
 * @package           Zen_Ig_Feed
 *
 * @wordpress-plugin
 * Plugin Name:       Zen Instagram Feed
 * Plugin URI:        mdhrseanli@gmail.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Hari Seanli
 * Author URI:        mdhrseanli@gmail.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       zen-ig-feed
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ZEN_IG_FEED_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-zen-ig-feed-activator.php
 */
function activate_zen_ig_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-zen-ig-feed-activator.php';
	Zen_Ig_Feed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-zen-ig-feed-deactivator.php
 */
function deactivate_zen_ig_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-zen-ig-feed-deactivator.php';
	Zen_Ig_Feed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_zen_ig_feed' );
register_deactivation_hook( __FILE__, 'deactivate_zen_ig_feed' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-zen-ig-feed.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_zen_ig_feed() {

	$plugin = new Zen_Ig_Feed();
	$plugin->run();

}
run_zen_ig_feed();

 

function zenigfeed_http_request($url){
    // persiapkan curl
    $ch = curl_init(); 
    // set url 
    curl_setopt($ch, CURLOPT_URL, $url);
    // set user agent    
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    // $output contains the output string 
    $output = curl_exec($ch); 
    // tutup curl 
    curl_close($ch);      
    // mengembalikan hasil curl
    return $output;
   	// return;
}


function zenigfeed_load_data_ig($data_post){
    $user_id = $data_post["user_id"];
    $token = $data_post["token"];
    $ig_url = $data_post["ig_url"];
    $source_method = $data_post["source_method"];

	$url = "https://graph.instagram.com/".$user_id."/media?access_token=".$token;
	$data_return_str = zenigfeed_http_request($url);
	$data_return_obj = json_decode($data_return_str);

	$list_media = $data_return_obj->data;
	$limit = 10;
	if(count($list_media) < $limit){
		$limit = count($list_media);
	}
	
	$media_list_arr = array();
	for ($i=0; $i < $limit; $i++) { 
		$media_id = $list_media[$i]->id;

		$url = "https://graph.instagram.com/".$media_id.
			   "?fields=id,media_type,media_url,permalink,thumbnail_url,username,timestamp&access_token=".$token;

		$data_return_str = zenigfeed_http_request($url);
		$data_return_obj = json_decode($data_return_str);
		array_push($media_list_arr, $data_return_obj);
	}

	$data_return = array();
	$data_return["user_id"] = $user_id;
	$data_return["token"] = $token;
    $data_return["ig_url"] = $ig_url;
    $data_return['source_method'] = $source_method;
	$data_return["media_list"] = $media_list_arr;
	return $data_return;
}

add_action( 'wp_ajax_zenigfeed_load_ig', 'load_ig_ajax_fun' );
function load_ig_ajax_fun(){
    $data_return = zenigfeed_load_data_ig($_POST);
    $data_return_str =  json_encode($data_return);
    echo $data_return_str;
    wp_die(); 
};



/*-- load data IG by ID --*/
function zenigfeed_load_data_ig_by_url($data_post){

    $user_id = $data_post["user_id"];
    $token = $data_post["token"];
    $ig_url = $data_post["ig_url"];
    $source_method = $data_post["source_method"];

    $url = $ig_url."?__a=1";
    $source_method = $source_method;
    if(strlen($ig_url) < 10){
        return;
    }
    if( $ig_url[strlen($ig_url) - 1] != "/"){
        $url  = $ig_url."/?__a=1";    
    }
    
    $data_return_str = zenigfeed_http_request($url);
    $data_return_obj = json_decode($data_return_str);
    
    $data_return_media_edges = $data_return_obj->graphql->user->edge_owner_to_timeline_media->edges;
    $limit = 12;
    $counter = 0;
    $media_list_arr = array();
    foreach ($data_return_media_edges as $value) {
        if($counter >= $limit){
            break;
        }

        $media_node = $value->node;

        $val_media_type = $media_node->is_video;
        $val_link = $media_node->shortcode;
        $permalink = "https://www.instagram.com/p/".$val_link."/";
        $media_url = $media_node->display_url;
        $thumbnail_url = $media_node->display_url;

        $media_type = "IMAGE";
        if($val_media_type == "true"){
            $media_type = "VIDEO";
        }
        if (array_key_exists('edge_sidecar_to_children', $media_node)) {
            $media_type = "CAROUSEL_ALBUM";
        }

        

        $data_list_n = array();
        $data_list_n["id"]="-";
        $data_list_n["media_type"]=$media_type;
        $data_list_n["media_url"]=$media_url;
        $data_list_n["permalink"]=$permalink;
        $data_list_n["thumbnail_url"]=$thumbnail_url;

        array_push($media_list_arr, $data_list_n);
        $counter+=1;
    }
    // print_r($data_list);
    $data_return = array();
    $data_return["user_id"] = $user_id;
    $data_return["token"] = $token;
    $data_return["ig_url"] = $ig_url;
    $data_return['source_method'] = $source_method;
    $data_return["media_list"] = $media_list_arr;
    return $data_return;

}

add_action( 'wp_ajax_zenigfeed_load_ig2', 'load_ig_ajax_fun2' );
function load_ig_ajax_fun2(){
    // $ig_url = $_POST['ig_url'];
    // $source_method = $_POST['source_method'];
    $data_return = zenigfeed_load_data_ig_by_url($_POST);
    $data_return_str =  json_encode($data_return);
    echo $data_return_str;
    wp_die(); 
};





/*-- scheduler start --*/

function svd_deactivate() {
    wp_clear_scheduled_hook( 'zenigfeed_refresh_feed' );
}

add_action('init', function() {
    add_action( 'zenigfeed_refresh_feed', 'fun_zenigfeed_refreshfeed' );
    register_deactivation_hook( __FILE__, 'svd_deactivate' );
 
    if (! wp_next_scheduled ( 'zenigfeed_refresh_feed' )) {
        // wp_schedule_event( time(), 'daily', 'zenigfeed_refresh_feed' );
        wp_schedule_event( strtotime('08:58:00'), 'daily', 'zenigfeed_refresh_feed' );
    }
});

function fun_zenigfeed_refreshfeed() {
	$posts_array = get_posts(
    	array(
        	'post_type'         => 'zenigfeed',
        	'post_status'       => 'publish',
        	'posts_per_page'    => -1,
        	'orderby'           => 'publish_date',
        	'order'             => 'DESC',
    	)
	);
	
	if( count($posts_array)>0 ):
    	foreach( $posts_array as $zenig_post ) :
    		$post_id = $zenig_post->ID;
    		$value = get_post_meta( $post_id, '_mtbox_zenigfeed_data_vk', true );

    		if(strlen($value) < 20){
    			continue;
    		}

    		$data_ig_obj = json_decode($value);
            $data_post = array();
            $data_post["user_id"] = $data_ig_obj->user_id;
            $data_post["token"] = $data_ig_obj->token;
            $data_post["ig_url"] = $data_ig_obj->ig_url;
            $data_post["source_method"] = $data_ig_obj->source_method;
            $data_post["media_list"] = $data_ig_obj->$media_list;        

            
    		if($data_ig_obj->source_method == "tabs_source_m1"){
                $data_return = zenigfeed_load_data_ig($data_post);
                $data_return_str =  json_encode($data_return);
                update_post_meta( $post_id, '_mtbox_zenigfeed_data_vk', $data_return_str );
            }
            if($data_ig_obj->source_method == "tabs_source_m2"){
                $data_return = zenigfeed_load_data_ig_by_url($data_post);
                $data_return_str =  json_encode($data_return);
                update_post_meta( $post_id, '_mtbox_zenigfeed_data_vk', $data_return_str );
            }

    	endforeach;
    endif;
}

/*-- scheduler end --*/