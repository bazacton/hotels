<?php
/**
 * @Add Meta Box For Events
 * @return
 *
 */
if (!class_exists('cs_event_meta')) {

    class cs_event_meta {

        public function __construct() {
            add_action('add_meta_boxes', array($this, 'cs_meta_event_add'));
        }

        function cs_meta_event_add() {
            add_meta_box('cs_meta_event', __('Event Options', 'cs_frame'), array($this, 'cs_meta_event'), 'events', 'normal', 'high');
        }

        function cs_meta_event($post) {
            global $post, $cs_theme_options, $page_option, $cs_form_meta;
            $cs_theme_options = get_option('cs_theme_options');
            $cs_builtin_seo_fields = isset($cs_theme_options['cs_builtin_seo_fields']) ? $cs_theme_options['cs_builtin_seo_fields'] : '';
            $cs_header_position = isset($cs_theme_options['cs_header_position']) ? $cs_theme_options['cs_header_position'] : '';
            ?>		
            <div class="page-wrap page-opts left" style="overflow:hidden; position:relative; height: 1432px;">
                <div class="option-sec" style="margin-bottom:0;">
                    <div class="opt-conts">
                        <div class="elementhidden">
                            <nav class="admin-navigtion">
                                <ul id="cs-options-tab">
                                    <li><a href="javascript:;" name="#tab-general-settings"><i class="icon-cog3"></i><?php _e('General', 'cs_frame'); ?></a></li>
                                    <?php
                                    if (function_exists('cs_subheader_element')) {
                                        ?>
                                        <li><a href="javascript:;" name="#tab-subheader-options"><i class="icon-indent"></i> <?php _e('Sub Header', 'cs_frame'); ?> </a></li>
                                        <?php
                                    }
                                    if (function_exists('cs_header_postition_element') && function_exists('cs_seo_settitngs_element')) {
                                        if ($cs_header_position == 'absolute') {
                                            ?>
                                            <li><a href="javascript:;" name="#tab-header-position-settings"><i class="icon-forward"></i><?php _e('Header Absolute', 'cs_frame'); ?></a></li>
                                        <?php } ?>
                                        <?php if ($cs_builtin_seo_fields == 'on') { ?>
                                            <li><a href="javascript:;" name="#tab-seo-advance-settings"><i class="icon-dribbble"></i> <?php _e('Seo Options', 'cs_frame'); ?></a></li>
                                        <?php
                                        }
                                    }
                                    ?>
                                    <li><a href="javascript:;" name="#tab-location-settings"><i class="icon-location2"></i><?php _e('Location', 'cs_frame'); ?></a></li>
                                    <li><a href="javascript:;" name="#tab-events-settings-cs-events"><i class="icon-briefcase"></i><?php _e('Event Options', 'cs_frame'); ?></a></li>
                                </ul>
                            </nav>
                            <div id="tabbed-content">
                                <div id="tab-general-settings">
                                    <?php
                                    $this->cs_event_general_settings();
                                    if (function_exists('cs_sidebar_layout_options')) {
                                        cs_sidebar_layout_options();
                                    }
                                    ?>
                                </div>
                                <?php
                                if (function_exists('cs_subheader_element')) {
                                    ?>
                                    <div id="tab-subheader-options">
                                    <?php cs_subheader_element(); ?>
                                    </div>
                                    <?php
                                }
                                if (function_exists('cs_header_postition_element') && function_exists('cs_seo_settitngs_element')) {
                                    if ($cs_header_position == 'absolute') {
                                        ?>
                                        <div id="tab-header-position-settings">
                                        <?php cs_header_postition_element(); ?>
                                        </div>
                                        <?php } ?>
                                        <?php if ($cs_builtin_seo_fields == 'on') { ?>
                                        <div id="tab-seo-advance-settings">
                                        <?php cs_seo_settitngs_element(); ?>
                                        </div>
                                    <?php
                                    }
                                }
                                ?>
                                <div id="tab-location-settings" style="width:100%;">
                                    <?php $this->cs_location_fields(); ?>
                                </div>
                                <div id="tab-events-settings-cs-events">
            <?php $this->cs_post_event_fields(); ?>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <?php
        }

        function cs_event_general_settings() {
            global $post, $cs_form_meta;
            $cs_form_meta->cs_form_checkbox_render(
                    array('name' => __('Social Sharing', 'cs_frame'),
                        'id' => 'post_social_sharing',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => '',
                    )
            );
            $cs_form_meta->cs_form_checkbox_render(
                    array('name' => __('Tags', 'cs_frame'),
                        'id' => 'post_tags_show',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => '',
                    )
            );
        }

        function cs_location_fields() {
            global $post, $cs_form_meta;

            if (function_exists('cs_enqueue_location_gmap_script')) {
                cs_enqueue_location_gmap_script();
            }

            $cs_form_meta->cs_form_checkbox_with_field_render(
                    array('name' => __('Location Map', 'cs_frame'),
                        'id' => 'map_switch',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => '',
                        'field' => array('field_name' => __('', 'cs_frame'),
                            'field_id' => 'map_heading',
                            'field_std' => __('Event Location', 'cs_frame'),
                        )
                    )
            );
            $cs_form_meta->cs_form_text_render(
                    array('name' => __('Address', 'cs_frame'),
                        'id' => 'location_address',
                        'classes' => 'gllpSearchButton',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
            $cs_form_meta->cs_form_text_render(
                    array('name' => __('City / Town', 'cs_frame'),
                        'id' => 'loc_city',
                        'classes' => 'gllpSearchButton',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
            $cs_form_meta->cs_form_text_render(
                    array('name' => __('Post Code', 'cs_frame'),
                        'id' => 'loc_postcode',
                        'classes' => 'gllpSearchButton',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
            $cs_form_meta->cs_form_text_render(
                    array('name' => __('Region', 'cs_frame'),
                        'id' => 'loc_region',
                        'classes' => 'gllpSearchButton',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );

            if (function_exists('cs_get_countries')) {
                foreach (cs_get_countries() as $key => $val):
                    $countries_list[$val] = $val;
                endforeach;
                $cs_form_meta->cs_form_select_render(
                        array('name' => __('Country', 'cs_frame'),
                            'id' => 'loc_country',
                            'classes' => 'gllpSearchButton',
                            'std' => '',
                            'onclick' => '',
                            'status' => '',
                            'description' => '',
                            'options' => $countries_list,
                        )
                );
            }

            $cs_form_meta->cs_location_map_render(
                    array('name' => __('Search This Location on Map', 'cs_frame'),
                        'id' => 'event_map',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
        }

        /**
         * @Event Custom Fileds Function
         * @return
         *
         */
        function cs_post_event_fields() {
            global $post, $cs_theme_options, $page_option, $cs_form_meta;
            $event_repeat = get_post_meta($post->ID, 'cs_event_repeat', true);
            $event_all_day = get_post_meta($post->ID, 'cs_event_all_day', true);
            $all_day_status = 'show';
            if (isset($event_all_day) && $event_all_day == 'on') {
                $all_day_status = 'hide';
            }
            $cs_repeat_time = 'hide';

            if (isset($event_repeat) && $event_repeat != '0' && $event_repeat != '') {
                $cs_repeat_time = 'show';
            }

            $cs_repeated = 'no';

            if (isset($_GET['post']) && $_GET['post'] != '') {
                $cs_repeated = 'yes';
            }


            $cs_form_meta->cs_form_date_render(
                    array('name' => __('Event Start Date', 'cs_frame'),
                        'id' => 'event_from_date',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );


            cs_framework::cs_enqueue_timepicker_script();
            ?>
            <script>
                jQuery(function () {
                    jQuery('#cs_event_start_time').datetimepicker({
                        datepicker: false,
                        format: 'H:i',
                        formatTime: 'H:i',
                        step: 30,
                        onShow: function (at) {
                            this.setOptions({
                                maxTime: jQuery('#cs_event_end_time').val() ? jQuery('#cs_event_end_time').val() : false
                            })
                        }
                    });
                    jQuery('#cs_event_end_time').datetimepicker({
                        datepicker: false,
                        format: 'H:i',
                        formatTime: 'H:i',
                        step: 30,
                        onShow: function (at) {
                            this.setOptions({
                                minTime: jQuery('#cs_event_start_time').val() ? jQuery('#cs_event_start_time').val() : false
                            })
                        }
                    });


                    jQuery('#cs_event_from_date').datetimepicker({
                        format: 'm/d/Y',
                        timepicker: false,
                        onSelectDate: function (selectedDate) {
                            jQuery("#cs_event_to_date").datetimepicker({minDate: selectedDate});
                        }
                    });
                    jQuery('#cs_event_to_date').datetimepicker({
                        format: 'm/d/Y',
                        timepicker: false,
                        onSelectDate: function (selectedDate) {
                            jQuery("#cs_event_from_date").datetimepicker({maxDate: selectedDate});
                        }
                    });

                    jQuery('.is_event_allday').change(function () {
                        var checkbox = jQuery('.is_event_allday');
                        if (checkbox.attr('checked') == 'checked') {
                            jQuery("#wrapper_allday_event_wrap").hide();
                        } else {
                            jQuery("#wrapper_allday_event_wrap").show();
                        }
                    });
                });
            </script>
            <?php
            $cs_form_meta->cs_form_date_render(
                    array('name' => __('Event End Date', 'cs_frame'),
                        'id' => 'event_to_date',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
            $cs_form_meta->cs_form_checkbox_render(
                    array('name' => __('All Day', 'cs_frame'),
                        'id' => 'event_all_day',
                        'classes' => 'is_event_allday',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
            $cs_form_meta->cs_wrapper_start_render(
                    array('name' => __('Wrapper', 'cs_frame'),
                        'id' => 'allday_event_wrap',
                        'status' => $all_day_status,
                    )
            );
            $cs_form_meta->cs_form_text_render(
                    array('name' => __('Event Start Time', 'cs_frame'),
                        'id' => 'event_start_time',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
            $cs_form_meta->cs_form_text_render(
                    array('name' => __('Event End Time', 'cs_frame'),
                        'id' => 'event_end_time',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
            $cs_form_meta->cs_wrapper_end_render(
                    array('name' => __('Wrapper', 'cs_frame'),
                        'id' => 'allday_event_wrap',
                    )
            );
            # Repeat		
            if (isset($cs_repeated) && $cs_repeated == 'no') {
                $cs_form_meta->cs_form_select_render(
                        array('name' => __('Repeat', 'cs_frame'),
                            'id' => 'event_repeat',
                            'classes' => '',
                            'std' => '',
                            'description' => __('Repeat how many time', 'cs_frame'),
                            'onclick' => 'toggle_with_value',
                            'status' => '', // Hide OR Show
                            'options' => array('0' => __('-- Never Repeat --', 'cs_frame'), '+1 day' => __('Every Day', 'cs_frame'), '+1 week' => __('Every Week', 'cs_frame'), '+1 month' => __('Every Month', 'cs_frame')),
                        )
                );
                $cs_form_meta->cs_wrapper_start_render(
                        array('name' => __('Wrapper', 'cs_frame'),
                            'id' => 'repeat_event',
                            'status' => $cs_repeat_time,
                        )
                );
                for ($i = 1; $i <= 25; $i++) {
                    $number[$i] = $i;
                }
                $cs_form_meta->cs_form_select_render(
                        array('name' => __('Repeat how many time', 'cs_frame'),
                            'id' => 'event_num_repeat',
                            'classes' => '',
                            'std' => '',
                            'description' => '',
                            'onclick' => '',
                            'status' => '', // Hide == hide OR Show == show
                            'options' => $number,
                        )
                );
                $cs_form_meta->cs_wrapper_end_render(
                        array('name' => __('Wrapper', 'cs_frame'),
                            'id' => 'repeat_event',
                        )
                );
            }
            $cs_form_meta->cs_heading_render(
                    array('name' => __('Ticket Option', 'cs_frame'),
                        'id' => 'ticket_options_wrap',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                    )
            );
            $cs_form_meta->cs_form_text_render(
                    array('name' => __('Title', 'cs_frame'),
                        'id' => 'ticket_title',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
            $cs_form_meta->cs_form_text_render(
                    array('name' => __('Url', 'cs_frame'),
                        'id' => 'buy_url',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
            $cs_form_meta->cs_heading_render(
                    array('name' => __('Event Ticket Price', 'cs_frame'),
                        'id' => 'ticket_options_wrap',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                    )
            );
            $cs_form_meta->cs_form_text_render(
                    array('name' => __('Event Ticket Price', 'cs_frame'),
                        'id' => 'prices_title',
                        'classes' => '',
                        'std' => __('Event Ticket Price', 'cs_frame'),
                        'description' => '',
                        'hint' => ''
                    )
            );
            $cs_form_meta->cs_form_textarea_render(
                    array('name' => __('Add Prices Shortcode', 'cs_frame'),
                        'id' => 'prices_shortcode',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
            $cs_form_meta->cs_heading_render(
                    array('name' => __('Status', 'cs_frame'),
                        'id' => 'event_status_head',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                    )
            );
            $cs_form_meta->cs_form_text_render(
                    array('name' => __('Event Status', 'cs_frame'),
                        'id' => 'event_status',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
            $cs_form_meta->cs_form_color_render(
                    array('name' => __('Color', 'cs_frame'),
                        'id' => 'status_color',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );


            $cs_form_meta->cs_heading_render(
                    array('name' => __('Event Organizer', 'cs_frame'),
                        'id' => 'event_organizer_head',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                    )
            );

            $cs_form_meta->cs_form_text_render(
                    array('name' => __('Organizer', 'cs_frame'),
                        'id' => 'event_organizer',
                        'classes' => '',
                        'std' => '',
                        'onclick' => '',
                        'status' => '',
                        'description' => '',
                    )
            );

            $cs_form_meta->cs_form_hidden_render(
                    array('name' => __('Repeat Access', 'cs_frame'),
                        'id' => 'repeat_access',
                        'classes' => '',
                        'std' => isset($_GET['post']) ? $_GET['post'] : '',
                        'type' => '', // Type : array for arrays and for single leave it empty,
                        'return' => 'echo' // return type : echo OR return
                    )
            );
        }

    }

    return new cs_event_meta();
}
