<?php
/**
 *
 *@Reviews
 *
 */
add_filter('manage_cs-reviews_posts_columns', 'cs_reviews_columns_add');
	function cs_reviews_columns_add($columns) {
		$columns['users'] 		= 'Users';
		$columns['room'] 		= 'Room';
		$columns['rating'] 		= 'Rating';
		return $columns;
}
add_action('manage_cs-reviews_posts_custom_column', 'cs_reviews_columns',10, 2);
	function cs_reviews_columns($name) {
		global $post,$cs_plugin_options;
		$rating = 0;
		$cs_rating_options = $cs_plugin_options["cs_dyn_reviews_options"];
		$rating = 0;
		$rating_array = array();
		
		if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
			foreach($cs_rating_options as $rating_key=>$rating){
				if(isset($rating_key) && $rating_key <> ''){
					$rating_title = $rating['cs_dyn_reviews_title'];
					$rating_slug  = $rating['dyn_reviews_id'];
					$rating_array[] = get_post_meta($post->ID, (string)$rating_slug, true);
				}
			}
		}
		
		$cs_rating_perctage = '0';
		if(isset($rating_array) && count($rating_array)>0){
			$rating = round(array_sum($rating_array)/count($cs_rating_options), 2);
			$cs_rating_perctage = ($rating/5)*100;
		}
		
		$var_cp_reviews_members = get_post_meta($post->ID, "cs_reviews_user", true);
		$cs_reviews_room 		= get_post_meta($post->ID, "cs_reviews_room", true);
		
		switch ($name) {
			case 'users':
				echo get_the_author_meta('display_name', $var_cp_reviews_members);
			break;
			case 'room':
				echo '<a href="'.get_edit_post_link($cs_reviews_room).'">'.get_the_title($cs_reviews_room).'</a>';
			break;
			case 'rating':
				echo '<div class="cs-ratingstar">
						<span style="width:'.$cs_rating_perctage.'%;"></span>';
				echo '</div>';
			break;

		}
}

/**
 *
 *@Register Post Type Reviews
 *
 */	
if(!class_exists('post_type_reviews')){
	
	class post_type_reviews{
	
			/**
			 * The Constructor
			 */
			public function __construct()
			{
				// register actions
				add_action('init', array(&$this, 'cs_reviews_init'));
			}
			
			/**
			 * hook into WP's init action hook
			 */
			public function cs_reviews_init()
			{
				// Initialize Post Type
				$this->cs_reviews_register();
			}
			
			public function cs_reviews_register(){
				$labels = array(
					'name' =>__('Reviews', 'booking'),
					'add_new_item' =>__('Add New Review','booking'), 
					'edit_item' =>__('Edit Review','booking'), 
					'new_item' =>__('New Review Item','booking'), 
					'add_new' =>__('Add New Review','booking'), 
					'view_item' =>__('View Reviews Item','booking'), 
					'search_items' =>__('Search Reviews','booking'), 
					'not_found' =>__('Nothing found','booking'), 
					'not_found_in_trash' =>__('Nothing found in Trash','booking'), 
					'parent_item_colon' => ''
				);
				$args = array(
					'labels' => $labels,
					'public' => true,
					'publicly_queryable' => true,
					'show_ui' => true,
					'query_var' => true,
					'menu_icon' => 'dashicons-admin-post',
					'show_in_menu' => 'edit.php?post_type=rooms',
					'rewrite' => true,
					'capability_type' => 'post',
					'hierarchical' => false,
					'menu_position' => null,
					'supports' => array('title','editor')
				); 
				register_post_type( 'cs-reviews' , $args );
				
			}	
	}
	new post_type_reviews();
}