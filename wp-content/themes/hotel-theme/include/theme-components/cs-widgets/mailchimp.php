<?php

/**
 * @MailChimp widget Class
 *
 *
 */
if ( ! class_exists( 'cs_mailchimp' ) ) { 
	class cs_mailchimp extends WP_Widget{	
	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */		 
	/**
	 * @init MailChimp Module
	 *
	 *
	 */
	 public function __construct() {
		
		parent::__construct(
			'cs_mailchimp', // Base ID
			__( 'CS: Mail Chimp','luxury-hotel' ), // Name
			array( 'classname' => 'widget_newsletter', 'description' =>__('Mail Chimp Newsletter Widget.', 'luxury-hotel'), ) // Args
		);
	}
	
	 /**
	 * @MailChimp html form
	 *
	 *
	 */
	 function form($instance){
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = $instance['title'];
		$description = isset( $instance['description'] ) ? esc_attr( $instance['description'] ) : '';
	?>
        <p>
          <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"><?php _e('Title','luxury-hotel');?>
            <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
          </label>
        </p>        
        <p>
          <label for="<?php echo cs_allow_special_char($this->get_field_id('description')); ?>"><?php _e('Description','luxury-hotel');?>
            <textarea class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('description')); ?>" name="<?php echo cs_allow_special_char($this->get_field_name('description')); ?>"><?php echo cs_allow_special_char($description); ?></textarea>
          </label>
        </p>        
        <?php
        }		
		/**
		 * @MailChimp update form data
		 *
		 *
		 */
		 function update($new_instance, $old_instance){
			  $instance = $old_instance;
			  $instance['title'] = $new_instance['title'];
			  $instance['description'] = $new_instance['description'];			
			  return $instance;
		 }
		 /**
		 * @Display MailChimp widget
		 *
		 *
		 */
		 function widget($args, $instance){
			  global $cs_node;		
			  extract($args, EXTR_SKIP);
			  $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
			  $description = empty($instance['description']) ? ' ' : apply_filters('widget_title', $instance['description']);				
			  echo cs_allow_special_char($before_widget);		
			  if (!empty($title) && $title <> ' '){
				  echo cs_allow_special_char($before_title);
				  echo cs_allow_special_char($title);
				  echo cs_allow_special_char($after_title);
			  }		
			  global $wpdb, $post;		
			  //wp_reset_query();
			   /**
				 * @Display MailChimp
				 *
				 *
				 */				 
				if ( function_exists( 'cs_custom_mailchimp' ) ) { echo cs_custom_mailchimp($description); }				
			    echo cs_allow_special_char($after_widget);
			  }
		  }
}

if (function_exists('cs_widget_register')) {
    cs_widget_register('cs_mailchimp');
}
?>