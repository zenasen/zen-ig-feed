<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       mdhrseanli@gmail.com
 * @since      1.0.0
 *
 * @package    Zen_Ig_Feed
 * @subpackage Zen_Ig_Feed/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Zen_Ig_Feed
 * @subpackage Zen_Ig_Feed/includes
 * @author     Hari Seanli <mdhrseanli@gmail.com>
 */
class Zen_Ig_Feed {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Zen_Ig_Feed_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'ZEN_IG_FEED_VERSION' ) ) {
			$this->version = ZEN_IG_FEED_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'zen-ig-feed';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Zen_Ig_Feed_Loader. Orchestrates the hooks of the plugin.
	 * - Zen_Ig_Feed_i18n. Defines internationalization functionality.
	 * - Zen_Ig_Feed_Admin. Defines all hooks for the admin area.
	 * - Zen_Ig_Feed_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-zen-ig-feed-loader.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-zen-ig-feed-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/zen-ig-feed-admin-display.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-zen-ig-feed-admin.php';
	

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-zen-ig-feed-public.php';


		$this->loader = new Zen_Ig_Feed_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Zen_Ig_Feed_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Zen_Ig_Feed_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Zen_Ig_Feed_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin_display = new Zen_Ig_Feed_Admin_Display( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_admin_display, 'register_cpt_zenigfeed' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin_display, 'register_zen_igfeed_metabox' );
		$this->loader->add_action( 'save_post', $plugin_admin_display, 'save_igfeed_metabox' );

		// if (!wp_next_scheduled('zenigfeed_refresh_feed')) {
		// 	wp_schedule_event( strtotime('14:03:00'), 'daily', 'zenigfeed_refresh_feed' );
		// }
		// $this->loader->add_action( 'zenigfeed_refresh_feed', $plugin_admin_display, 'fun_zenigfeed_refreshfeed' );
		// $this->loader->add_action( 'cron_job', $plugin_admin_display, 'register_zen_igfeed_metabox' );
		// $this->loader->add_action( 'my_task_hook', $plugin_admin_display, 'save_igfeed_metabox' );

	}

	

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Zen_Ig_Feed_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_shortcode( 'zen-ig-feed', $plugin_public, 'zenigfeed_shortcode_fun' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Zen_Ig_Feed_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}