<?php
/**
 *  File Type: Rooms Block
 */

if( ! class_exists('cs_rooms_block') ) {
	
    class cs_rooms_block {
		
		public function __construct() {
			add_action( 'admin_menu', array(&$this, 'cs_block_menu') );
			add_action('wp_ajax_cs_get_rooms', array(&$this, 'cs_get_rooms'));
			add_action('wp_ajax_cs_update_rooms', array(&$this, 'cs_update_rooms'));
		}
		
		//add submenu page
		public function cs_block_menu() {
			
			add_submenu_page('edit.php?post_type=rooms', __('Block Rooms', 'booking'), __('Block Rooms', 'booking'), 'manage_options', 'cs_blocks', array(&$this, 'cs_block_meta'));
		}
		
		//add price fields
		public function cs_block_meta() {
			
			global $cs_form_fields;
			?>
            <div class="theme-wrap fullwidth">
                <div class="row">
                    <div id="message" class="cs-update-message updated notice notice-success" style="display:none;">
                        <p></p>
                    </div>
                    <div class="room-block-wrap cs-customers-area">
						<div class="cs-title"><h2><?php _e('Block Rooms', 'booking');?></h2></div>
                    	<div class="cs_table_data cs_loading">
                            <form action="">
                                <div class="cs-block-header">
                                   <select name="cs_rooms" id="cs_get_rooms" class="dropdown">
                                        <option value=""><?php _e('Select Room', 'booking') ?></option>
                                        <?php
                                            $cs_room_types = array();
                                            $cs_args = array( 'posts_per_page' => '-1', 'post_type' => 'rooms_capacity', 'orderby'=>'ID', 'post_status' => 'publish' );
                                            $cust_query = get_posts($cs_args);
                                            
                                            if( sizeof($cust_query) > 0 ) {
                                                
                                                foreach( $cust_query as $type ) {
                                                    echo '<option value="'.$type->ID.'">'.get_the_title($type->ID).'</option>';
                                                }
                                                wp_reset_postdata();
                                            }
                                        ?>
                                    </select>
                                    <div id="cs-loader"></div>
                                </div>
									<?php wp_hotel_booking::cs_data_table_style_script(); ?>
									<script type="text/javascript">
										jQuery(document).ready(function() {
											jQuery("#cs_block_data").dataTable({
												"paging":   true,
												"pagingType": "simple_numbers",
												"ordering": true,
												"info":     false,
												"fnDrawCallback": function(oSettings) {
													if(jQuery("#cs_block_data").find("tr:not(.ui-widget-header)").length <= 4){
													} else {
													}
												}
											});
										});
									</script>                              
                                    <div class="rooms-data-wrapper">
                                     	<table id="cs_block_data" class="display" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="15%"><?php _e('Id', 'booking'); ?></th>
                                                    <th width="15%"><?php _e('Room Reference No', 'booking'); ?></th>
                                                    <th width="15%"><?php _e('Reason', 'booking'); ?></th>
                                                    <th width="15%"><?php _e('Action', 'booking'); ?></th>
                                                </tr>
                                            </thead>
                                    </table>
                            	</div>
                    		</form>
                        </div>
                    </div>
                </div>
           </div>
           <?php
		}
		
		// Get Rooms Data
		public function cs_get_rooms(){
			global $post;
			$json		= array();
			$room_id	= (isset($_REQUEST['room_id']) and $_REQUEST['room_id'] <> '') ? $_REQUEST['room_id'] : '';
			
			if ( $room_id =='' ){
				$json['type']		= 'error';
				$json['message']	= '<i class="icon-times"></i> Some error occur, pleae try again later.';
			} else {
				
				$list_item	= '<script>jQuery(document).ready(function() {	cs_update_rooms(); });</script>';
				$list_item	.= '<script type="text/javascript">
									jQuery(document).ready(function() {
										jQuery("#cs_block_data").dataTable({
											"paging":   true,
											"pagingType": "simple_numbers",
											"ordering": true,
											"info":     false,
											"pageLength": 15,	
											"fnDrawCallback": function(oSettings) {
												if(jQuery("#cs_block_data").find("tr:not(.ui-widget-header)").length <= 4){
												} else {
												}
											}
										});
									});
								</script>'; 	
				$data_attr	= get_post_meta($room_id, 'cs_room_meta_data', true);
				if( isset( $data_attr ) && $data_attr !='' ) {
					$cs_room_data = '';
					$cs_room_meta = get_post_meta($room_id, 'cs_room_meta_data', false);
					$data_counter	 = 0;
					$list_item	.='<table id="cs_block_data" class="display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th width="15%">'.__('Id', 'booking').'</th>
								<th width="15%">'.__('Room Reference No', 'booking').'</th>
								<th width="15%">'.__('Reason', 'booking').'</th>
								<th width="15%">'.__('Action', 'booking').'</th>
							</tr>
						</thead>';
						foreach( $cs_room_meta[0] as $key => $room_reference  ){
							$data_counter++;
							$cs_status	= 'In-active';
							$class		= 'in-active';
							if( $room_reference['status'] == 'active' ) {
								$cs_status	= 'Active';
								$class		= 'active';
							}
							$color	= 'cs-odd';
							if( $data_counter%2 == 0 ) {
								$color	= 'cs-even';
							}
							$list_item	.= '<tr class="rooms-data">
								<td  width="15%">'.$data_counter.'</td>
								<td  width="15%">'.$room_reference['reference_no'].'</td>
								<td  width="15%"  class="cs-block-reason">
								<a href="javascript:;" class="edit-reason"><i class="icon-pencil3"></i></a>
								<a href="javascript:;" data-key='.$key.' data-reference='.$room_id.' data-status="no" class="edit-reason-update" style="display:none">
								<i class="icon-cycle"></i></a>
								<input  type="text" value="'.$room_reference['reason'].'" style="display:none" />
								<p>'.$room_reference['reason'].'</p>
								
								</td>
								<td  width="15%" class="cs-block-action"><a class="'.$class.'" href="javascript:;"  data-status="yes" data-key='.$key.' data-reference='.$room_id.' >'.$cs_status.'</a>
								<span class="cs-spinner"></span>
								</td>
							</tr>';
					}

				} else {
					$list_item	.= '<div class="rooms-data">'.__('No Rooms Found.', 'booking').'</div>';	
				}
				$list_item	.= '</table>';
				
				$json['type']		= 'success';
				$json['data']		= $list_item;
			}
			echo json_encode( $json );
			die();
		}
		
		// Update Rooms Data
		public function cs_update_rooms(){
			global $post;

			$json				= array();
			$data_key	    	= $_REQUEST['data_key'];
			$room_id	    	= $_REQUEST['room_id'];
			$reason	    		= $_REQUEST['reason'];
			$status_update	    = $_REQUEST['status'];

			if ( $data_key =='' ){
				$json['type']		= 'error';
				$json['message']	= __('Some error occur, please try again later.','booking');
			} else {
				if( isset( $room_id ) && $room_id !='' ){
					$cs_room_meta = get_post_meta($room_id, 'cs_room_meta_data', false);
					
					if( isset( $cs_room_meta[0][$data_key] ) ) {
						$room_data	= array();
						$room_data[$data_key]['id']				= $cs_room_meta[0][$data_key]['id'];
						$room_data[$data_key]['reference_no']	= $cs_room_meta[0][$data_key]['reference_no'];
						//$room_data[$data_key]['booked']			= $cs_room_meta[0][$data_key]['booked'];
						//$room_data[$data_key]['start_date']		= $cs_room_meta[0][$data_key]['start_date'];
						//$room_data[$data_key]['end_date']		= $cs_room_meta[0][$data_key]['end_date'];
						
						if( $cs_room_meta[0][$data_key]['status'] == 'active' ) {
							$status	= 'in-active';
							$json['message']	= __('Room is de-activated.','booking');
						} else{
							$status	= 'active';
							$json['message']	= __('Room is activated.','booking');
						}
						
						if( $status_update == 'no' ) {
							$status	= $cs_room_meta[0][$data_key]['status'];
						}
						
						$room_data[$data_key]['status']			= $status;
						$room_data[$data_key]['reason']			= $reason;
						
						$new_data	= array_merge($cs_room_meta[0],$room_data);
						
						update_post_meta($room_id,'cs_room_meta_data',$new_data);
						$json['type']		=__('success','booking');
						$json['status']		= $status;
						
					
					} else{
						$json['type']		=__('error','booking');
						$json['message']	= __('Some error occur, pleae try again later.','booking');
					}

					
				} else{
					$json['type']		=__('error','booking');
					$json['message']	= __('Some error occur, pleae try again later.','booking');
				}
		  }
			  echo json_encode( $json );
			  die();
		}
	}
	
	new cs_rooms_block();
}

