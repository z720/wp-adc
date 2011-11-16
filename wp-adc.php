<?php
/*
Plugin Name: Author Default Category
Plugin URI: http://github.com/z720/wp-adc
Description: This plugins allow any author to select its own default category.
Author: Sebastien Erard
Version: 1.0-beta
Author URI: http://z720.net
*/

class AuthorDefaultCategory {

	private $opt_name = 'default-category';

	private function isUserContext() {
		global $current_screen;
		//die(print_r((WP_ADMIN && !($current_screen->id == 'options-writing')) == false, true));
		// User logged in and can edit posts (Minimum)
 		$ret = (get_current_user_id() != 0 && user_can( get_current_user_id(), 'edit_posts'))
 		// Override in admin except options-writing to allow global default category to be edited
 			&& (   (WP_ADMIN && !($current_screen->id == 'options-writing') ) 
		// Override in XmlRPC request
 				|| defined(XMLRPC_REQUEST) );
 		return $ret;
	}
	
	private function isGlobalContext() {
		return $this->isUserContext();
	}

	/**
	 * get Option default category override
	 */
	public function user_default_category($original_value){
		// Override in the context of a user action
		if($this->isUserContext()) {
			$user_default_category = get_user_option($this->opt_name,get_current_user_id());
			if($user_default_category) {
				return $user_default_category;
			}
		}
		return $original_value;
	}

	/**
	 * Constructor : init WordPress hooks
	 * @since 1.0
	 */
	public function __construct() {
		add_action('personal_options', array(&$this, 'show_personal_options'));
		// profile save own or for admin
		add_action( 'personal_options_update', array( &$this, 'save_personnal_options') );
		add_action( 'edit_user_profile_update', array( &$this, 'save_personnal_options') );
		
		// option override fro xmlrpc and post insert
		add_filter('option_default_category', array(&$this, 'user_default_category'));
	}
	
	/**
	 * Save the option
	 */
	public function save_personnal_options($user_id) {
	 	// Save only if current user can and edited user can have a default category
		if ( !current_user_can( 'edit_user', $user_id ) || !user_can( $user_id, 'edit_posts') ) return false;
		// Save the option (blog dependent)
		$cat = get_category($_POST[$this->opt_name]);
		//die(print_r($cat,true));
		if( !is_wp_error( $cat ) ) {
			update_user_option( $user_id, $this->opt_name, $cat->term_id, false );
		}
	}
	
	/**
	 * Show the default category field in the user profile
	 */
	public function show_personal_options($user) {
			global $current_screen;
		if ( !user_can( $user->ID, 'edit_posts') ) return false;
	    // Args for standard wp category dropdown categories select
		$args = array(
				'show_option_all'    => '',
				'show_option_none'   => '',
				'orderby'            => 'ID', 
				'order'              => 'ASC',
				'show_last_update'   => 0,
				'show_count'         => 0,
				'hide_empty'         => 0, 
				'child_of'           => 0,
				'exclude'            => '',
				'echo'               => 1,
				'selected'           => get_user_option($this->opt_name,$user->ID),
				'hierarchical'       => 0, 
				'name'               => $this->opt_name,
				'id'                 => $this->opt_name,
				'class'              => 'postform',
				'depth'              => 0,
				'tab_index'          => 0,
				'taxonomy'           => 'category',
				'hide_if_empty'      => false ); 
				// Template for a single field ?>
<tr class="default-category">
<th scope="row"><?php _e('Default Category')?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Default Category') ?></span></legend>
<label for="default-category">
<?php wp_dropdown_categories($args); /* translators: Show admin bar when viewing site / _e( 'when viewing site' );*/ ?></label></fieldset>
</td>
</tr><?php	
	}	

}

// anonymous instance
new AuthorDefaultCategory();