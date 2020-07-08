<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       mdhrseanli@gmail.com
 * @since      1.0.0
 *
 * @package    Zen_Ig_Feed
 * @subpackage Zen_Ig_Feed/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Zen_Ig_Feed
 * @subpackage Zen_Ig_Feed/admin
 * @author     Hari Seanli <mdhrseanli@gmail.com>
 */
class Zen_Ig_Feed_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		

	}

	/**
	 * Register the stylesheets for the admin area.
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
		
		wp_enqueue_style('plugin_name-admin-ui-css', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css', array(),  $this->version, false );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/zen-ig-feed-admin.css', array(), $this->version, 'all' );


	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_enqueue_script( 'jquery-ui-tabs');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/zen-ig-feed-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );

	}

	

}