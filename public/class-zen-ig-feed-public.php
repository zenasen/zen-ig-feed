<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       mdhrseanli@gmail.com
 * @since      1.0.0
 *
 * @package    Zen_Ig_Feed
 * @subpackage Zen_Ig_Feed/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Zen_Ig_Feed
 * @subpackage Zen_Ig_Feed/public
 * @author     Hari Seanli <mdhrseanli@gmail.com>
 */
class Zen_Ig_Feed_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Zen_Ig_Feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Zen_Ig_Feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/zen-ig-feed-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Zen_Ig_Feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Zen_Ig_Feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/zen-ig-feed-public.js', array( 'jquery' ), $this->version, false );

	}

	public function zenigfeed_shortcode_fun($atts){
		$a = shortcode_atts( array(
			'id' => '',
			'limit' => '100',
			'layout' => 'grid',
		), $atts );

		
		if ($a["id"] == "") {
			return "<p>Need ID</p>";
		}

		$limit = $a["limit"];
		$layout = $a["layout"];


		$id = $a["id"];
		$zigfeed_data_str = get_post_meta( $id, '_mtbox_zenigfeed_data_vk', true );
		$zigfeed_data_obj = json_decode($zigfeed_data_str);

		$zigfeed_data_media_obj = $zigfeed_data_obj->media_list;
		// $zigfeed_data_media_obj = json_decode($zigfeed_data_media);


		$el_items = "";
		$counter = 0;
		foreach ($zigfeed_data_media_obj as $value) {
			$counter+=1;
			if($counter > $limit){
				break;
			}

			$id = $value->id;
			$type = $value->media_type;
			$el_img = $value->media_url;	
			if($type == "VIDEO"){
				$el_img = $value->thumbnail_url;
			}
			$el_link = $value->permalink;
			
			// $el_link
			$el_items .= '<div class="ig-item '.$type.' ">
							<a href="'.$el_link.'" target="_blank" rel="nofollow noopener">
								<img class="ig-img" xsrc="'.$el_img.'" alt="Instagram Image">
							</a>
						  </div>';	
		}
		$html_return = '<div class="zigfeed-container '.$layout.'"><div class="zrow">'.$el_items.'</div>';
		return  $html_return;
		
	}

}
