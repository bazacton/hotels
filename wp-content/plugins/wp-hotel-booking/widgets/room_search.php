<?php
/**
 * @ Room Search widget Class
 *
 *
 */
class room_search extends WP_Widget {
    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */

    /**
     * @init Room Search Module
     *
     *
     */
    
	public function __construct() {
		
		parent::__construct(
			'room_search', // Base ID
			__( 'CS : Room Search','booking' ), // Name
			array( 'classname' => 'widget_text', 'description' =>__('Footer Contact Information', 'booking'), )
		);
	}

    /**
     * @Room Search html form
     *
     *
     */
    function form($instance) {
        $instance  = wp_parse_args((array) $instance);
        $title     = isset($instance['title']) ? esc_attr($instance['title']) : '';

        $randomID  = rand(40, 9999999);
        ?>    
        <div style="margin-top:0px; float:left; width:100%;">
            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> <span><?php _e('Title','booking');?></span>
                    <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
        </div>
        <?php
    }

    /**
     * @Update Info html form
     *
     *
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    /**
     * @Widget Info html form
     *
     *
     */
    function widget($args, $instance) {
        global $cs_plugin_options;
        extract($args, EXTR_SKIP);
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $title = htmlspecialchars_decode(stripslashes($title));
        
        echo cs_allow_special_char($before_widget);
        if (!empty($title) && $title <> ' ') {
            echo cs_allow_special_char($before_title) . $title . $after_title;
        }
		
		$cs_page_id	= isset( $cs_plugin_options['cs_reservation'] ) && $cs_plugin_options['cs_reservation'] !='' && absint($cs_plugin_options['cs_reservation']) ? $cs_plugin_options['cs_reservation'] : '';
		$cs_max_childs	= isset( $cs_plugin_options['cs_childs'] ) && $cs_plugin_options['cs_childs'] !='' && absint($cs_plugin_options['cs_childs']) ? $cs_plugin_options['cs_childs'] : '10';
		$cs_max_adults	= isset( $cs_plugin_options['cs_adults'] ) && $cs_plugin_options['cs_adults'] !='' && absint($cs_plugin_options['cs_adults']) ? $cs_plugin_options['cs_adults'] : '10';
		wp_hotel_booking::cs_enqueue_datepicker_script();
		
		$search_link  = add_query_arg( array('action'=>'booking' ),  esc_url( get_permalink( $cs_page_id ) ) );
        $cs_hotels	= get_option('cs_hotel_options');
		?>
        <div class="reservation-form">
        	<script>
				 jQuery(document).ready(function($) {
					 cs_widget_datepicker();
				 });
			</script>
          <div class="reservation-inner col-md-2 cs-search-room-elm rooms-options-data">
            <form class="form-reviews" method="post" action="<?php echo esc_url( $search_link );?>" id="room-seach">
              <ul class="review-style box-br-style" id="search-wraper" data-adults="<?php echo esc_attr( $cs_max_adults );?>" data-childs="<?php echo esc_attr( $cs_max_childs );?>">
                <li> <span class="select-style-two">
                  <label for=""><?php _e('Select Hotels1','booking')?></label>
                  <label id="Deadline" class="cs-check-in cs-calendar-combo">
                   <select name="cs_hotels" id="cs-hotels">
                   <?php 
					   if( isset( $cs_hotels ) && is_array( $cs_hotels ) ) {
							foreach( $cs_hotels as $key => $hotel ) {
								echo '<option value="'.$key.'">'.$hotel['cs_hotel_name'].'</option>';
							}
					   } else{
							echo '<option value="">'. _e('Select Hotel','booking').'</option>';
					   }
				   ?>
                   </select>
                  </label>
                  </span>
                </li>
                <li> <span class="select-style-two">
                  <label for=""><?php _e('Check in Date','booking')?></label>
                  <label id="Deadline" class="cs-check-in cs-calendar-combo">
                    <input type="text" name="date_from" id="check-in-date" value="<?php echo date('d/m/Y');?>" placeholder="DD/MM/YYYY">
                  </label>
                  </span>
                </li>
                
                <li> <span class="select-style-three">
                  <label for=""><?php _e('Check out Date','booking')?></label>
                  <label id="Deadline" class="cs-check-out cs-calendar-combo">
                    <input type="text" autocomplete="off" name="date_to" id="check-out-date" value=""  placeholder="DD/MM/YYYY">
                  </label>
                  </span>
                </li>
                <li class="rooms">
                  <label for=""><?php _e('No. of Rooms','booking')?></label>
                  <span class="select-style">
                       <select name="cs-rooms" id="cs-booking-rooms">
                        <?php 
                            for( $i = 1; $i <= 2; $i++ ) {
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                        ?>
                       </select>
                   </span>
                </li>
                <li class="select-options">
                    <div class="select-area">
                          <span class="select-style">
                          <select id="cs_max_adults" class="booking-members" name="adults[]">
                             <?php 
                                for( $i = 1; $i <= $cs_max_adults; $i++ ) {
                                    echo '<option value="'.$i.'">'.$i.' '.__('Adult(s)','booking').'</option>';
                                }
                             ?>
                          </select>
                         </span> 
                    </div>
                    <div class="select-area">
                          <span class="select-style">
                          <select id="cs_max_childs" class="booking-members" name="childs[]">
							 <?php 
                                for( $i = 0; $i <= $cs_max_childs; $i++ ) {
                                    echo '<option value="'.$i.'">'.$i.' '.__('Child(s)','booking').'</option>';
                                }
                             ?>
                          </select>
                         </span> 
                    </div>
                </li>
                <li>
                  <input class="search-btn csbg-color" id="seach_room_btn" type="button" value="<?php _e("Search Room","booking")?>" name="Search Room">
                </li>
              </ul>
            </form>
          </div>
        </div>
        <?php echo cs_allow_special_char($after_widget);?>
       <?php 
    }
}

add_action('widgets_init', function (){ return register_widget("room_search"); });
?>