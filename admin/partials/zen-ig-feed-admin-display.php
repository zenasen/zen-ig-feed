<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       mdhrseanli@gmail.com
 * @since      1.0.0
 *
 * @package    Zen_Ig_Feed
 * @subpackage Zen_Ig_Feed/admin/partials
 */



class Zen_Ig_Feed_Admin_Display{

	private $plugin_name;
	private $version;
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}


	
	/**
	 * Meta box display callback.
	 *
	 */
	public function register_cpt_zenigfeed()
	{
		$labels = array(
			'name'                => _x( 'Zen IG Feeds', 'Post Type General Name', 'zen-ig-feed' ),
			'singular_name'       => _x( 'IG Feed', 'Post Type Singular Name', 'zen-ig-feed' ),
			'menu_name'           => __( 'Zen IG Feed', 'zen-ig-feed' ),
			'parent_item_colon'   => __( 'Parent IG Feed', 'zen-ig-feed' ),
			'all_items'           => __( 'All Feed', 'zen-ig-feed' ),
			'view_item'           => __( 'View Feed', 'zen-ig-feed' ),
			'add_new_item'        => __( 'Add New Feed', 'zen-ig-feed' ),
			'add_new'             => __( 'Add New', 'zen-ig-feed' ),
			'edit_item'           => __( 'Edit Feed', 'zen-ig-feed' ),
			'update_item'         => __( 'Update Feed', 'zen-ig-feed' ),
			'search_items'        => __( 'Search Feed', 'zen-ig-feed' ),
			'not_found'           => __( 'Not Found', 'zen-ig-feed' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'zen-ig-feed' ),
		);
		// Set other options for Custom Post Type
		$args = array(
			'label'               => __( 'zen_ig_feed', 'zen-ig-feed' ),
			'description'         => __( 'Zen IG Feed', 'zen-ig-feed' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'revisions',  ),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest' => true,
		);
		
		register_post_type( 'zenigfeed', $args );
	}


	public function display_tab_souce_content($post)
	{
		?>

		<div id="zenigfeed_source_method">
			<select id="select_source_method">
				<option value="tabs_source_m1">Method 1</option>
				<option value="tabs_source_m2" selected="true">Method 2</option>
			</select><!-- select_source_method -->

			<div id="tabs_source_m1" class="source_option">
				<label>IG ID </label>
				<input type="text" name="ig_id">
				<br>
				<label>Access Token</label>
				<input type="text" name="access_token">
			</div> <!-- tabs_source_m1 -->

			<div id="tabs_source_m2" class="source_option active">
				<label>IG Url </label>
				<input type="text" name="ig_url">
			</div> <!-- tabs_source_m2 -->
		</div>

		<?php
	}


	public function wpdocs_my_display_callback( $post ) {
		
        ?>

        <!-- shortcode -->
		<?php 
			$post_id = get_the_ID(); 
			$shortcode = '[zen-ig-feed id="'.$post_id.'"]';
		?>
		<label for="zen_ig_feed_shortcode">
        	<?php _e( 'Shortcode', 'zen-ig-feed' ); ?>
        </label>
        <input type="text" id="zen_ig_feed_shortcode" readonly name="zen_ig_feed_shortcode" value='<?php echo $shortcode; ?>' />
        <br>
        <!-- shortcode end  -->

		<div id="zenigfeed_plugin_dashboard">

			<ul>
				<li><a href="#tabs_source" ><?php _e( 'Data Source', 'zen-ig-feed' ); ?></a></li>
				<li><a href="#tabs_option" class="ui-tabs-active"><?php _e( 'Display Option', 'zen-ig-feed' ); ?></a></li>
			</ul>

			<!-- source-method -->
			<div id="tabs_source">
				<?php $this->display_tab_souce_content($post) ?>
			

				<!-- button for load -->
				<br>
				<button id="load_ig" class="button button-primary button-large">Load data IG</button>

				<!-- display-image -->
				<br>
				<br>
				<div class="data-ig-container">
					<div id="list_item_ig_origin" xclass="list-item-ig" style="display: none">
						<div class="img-wrapper">
							
						</div>
						<div class="text-wrapper">
						</div>
					</div>
				</div>

			</div><!-- tabs_source -->


			<div id="tabs_option">
				<h3>Tab Body</h3>
			</div><!-- tabs_option -->


			<!-- metabox -->
			<?php 
		        wp_nonce_field( 'metabox_zenigfeed_nk', 'metabox_zenigfeed_nonce' );
		        $value = get_post_meta( $post->ID, '_mtbox_zenigfeed_data_vk', true );
	        ?>
			<div style="">
				<label for="metabox_data_ig">
		            <?php _e( 'Data', 'zen-ig-feed' ); ?>
		        </label>
		        <input type="text" id="metabox_data_ig" name="metabox_data_ig" value="<?php echo esc_attr( $value ); ?>" />
			</div>
			<button id="display_ig" class="button button-primary button-large">Display Data IG</button>

		</div><!-- plugin_dashboard -->

		
        <?php

	}


	public function register_zen_igfeed_metabox() {
		add_meta_box( 'mtbox_data_ig', __( 'Data IG Metabox', 'zen-ig-feed' ), array($this, 'wpdocs_my_display_callback'), 'zenigfeed' );
	}

	public function save_igfeed_metabox( $post_id ) {
		 /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST['metabox_zenigfeed_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['metabox_zenigfeed_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'metabox_zenigfeed_nk' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( 'page' == $_POST['zenigfeed'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
 
        /* OK, it's safe for us to save the data now. */
 
        // Sanitize the user input.
        $mydata = $_POST['metabox_data_ig'];

        // Update the meta field.
        update_post_meta( $post_id, '_mtbox_zenigfeed_data_vk', $mydata );
        
		
	}



}
