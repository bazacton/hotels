<?php
/**
 * @Social Network widget Class
 *
 *
 */
if ( ! class_exists( 'cs_social_network_widget' ) ) { 
    class cs_social_network_widget extends WP_Widget{    
    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
     
    /**
     * @Social Network Module
     *
     *
     */
  
	  	public function __construct() {
		
		parent::__construct(
			'cs_social_network_widget', // Base ID
			__( 'CS : Social Newtork','luxury-hotel' ), // Name
			array( 'classname' => 'widget-socialnetwork', 'description' =>__('Social Newtork widge', 'luxury-hotel'), ) // Args
		);
	}
	    
    /**
     * @Social Network html form
     *
     *
     */
    function form($instance) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = $instance['title'];
        ?>
        <p>
        	<label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"><?php _e('Title','luxury-hotel');?> 
            <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size='40' name="<?php echo   cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
          </label>
        </p>
	<?php
      }      
    /**
     * @Social Network Update from data 
     *
     *
     */
     function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
      }      
    /**
     * @Social Network Widget
     *
     *
     */
     function widget($args, $instance){
            extract($args, EXTR_SKIP);
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$title = wp_specialchars_decode(stripslashes($title));
           	 echo cs_allow_special_char($before_widget);                
            if (!empty($title) && $title <> ' '){
                echo cs_allow_special_char($before_title);
                echo cs_allow_special_char($title);
                echo cs_allow_special_char($after_title);
            }
                global $wpdb, $post;
                echo '<div class="followus">';
               		cs_social_network_widget();
                echo '</div>';
                echo cs_allow_special_char($after_widget);
            }
        }
}
if (function_exists('cs_widget_register')) {
    cs_widget_register('cs_social_network_widget');
}
?>