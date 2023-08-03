<?php/** * @Rooms * @return html * */ if ( ! function_exists( 'cs_pb_rooms' ) ) {	function cs_pb_rooms($die = 0){		global $cs_node, $post;		$shortcode_element = '';		$filter_element = 'filterdrag';		$shortcode_view = '';		$output = array();		$counter = $_POST['counter'];		$cs_counter = $_POST['counter'];		if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {			$POSTID = '';			$shortcode_element_id = '';		} else {			$POSTID = $_POST['POSTID'];			$shortcode_element_id = $_POST['shortcode_element_id'];			$shortcode_str = stripslashes ($shortcode_element_id);			$PREFIX = 'cs_rooms';			$parseObject 	= new ShortcodeParse();			$output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );		}				$defaults = array('column_size' => '1/1','section_title'=>'','view'=>'','room_type'=>'','order'=>'DESC','orderby'=>'ID','room_excerpt'=>'255','rooms_pagination'=>'pagination','pagination'=>'10');			if(isset($output['0']['atts']))				$atts = $output['0']['atts'];			else 				$atts = array();			$rooms_element_size = '50';			foreach($defaults as $key=>$values){				if(isset($atts[$key]))					$$key = $atts[$key];				else 					$$key = $values;			 }			$name = 'cs_pb_rooms';			$coloumn_class = 'column_'.$rooms_element_size;		if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){			$shortcode_element = 'shortcode_element_class';			$shortcode_view = 'cs-pbwp-shortcode';			$filter_element = 'ajax-drag';			$coloumn_class = '';		}	?>    <div id="<?php echo esc_attr( $name.$cs_counter );?>_del" class="column  parentdelete <?php echo esc_attr( $coloumn_class );?> <?php echo esc_attr( $shortcode_view );?>" item="blog" data="<?php echo element_size_data_array_index($rooms_element_size)?>">      <?php cs_element_setting($name,$cs_counter,$rooms_element_size);?>      <div class="cs-wrapp-class-<?php echo intval( $cs_counter );?> <?php echo esc_attr( $shortcode_element );?>" id="<?php echo esc_attr( $name.$cs_counter );?>" data-shortcode-template="[cs_rooms {{attributes}}]"  style="display: none;">        <div class="cs-heading-area">          <h5><?php _e('Edit Rooms Options', 'booking') ?></h5>          <a href="javascript:cs_remove_overlay('<?php echo esc_attr($name.$cs_counter)?>','<?php echo esc_attr($filter_element);?>')" class="cs-btnclose"><i class="fa fa-times"></i></a> </div>        <div class="cs-pbwp-content">          <div class="cs-wrapp-clone cs-shortcode-wrapp">            <?php             if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>            <ul class="form-elements">                <li class="to-label"><label><?php _e('Section Title', 'booking') ?></label></li>                <li class="to-field">                    <input  name="section_title[]" type="text"  value="<?php echo esc_attr( $section_title );?>"   />                </li>                               </ul>            <ul class="form-elements">              <li class="to-label">                <label><?php _e('Rooms Design View', 'booking') ?></label>              </li>              <li class="to-field">                <div class="input-sec">                  <div class="select-style">				    <select name="view[]" class="dropdown">                      <option value="classic" <?php if($view == 'classic'){echo 'selected="selected"';}?>><?php _e('Classic', 'booking') ?></option>                      <option value="plain" <?php if($view == 'plain'){echo 'selected="selected"';}?>><?php _e('Plain', 'booking') ?></option>                      <option value="modern" <?php if($view == 'modern'){echo 'selected="selected"';}?>><?php _e('Modern 3 Column', 'booking') ?></option>                      <option value="modern-four" <?php if($view == 'modern-four'){echo 'selected="selected"';}?>><?php _e('Modern 4 Column', 'booking') ?></option>                      <option value="slider" <?php if($view == 'slider'){echo 'selected="selected"';}?>><?php _e('Slider', 'booking') ?></option>                      <option value="grid" <?php if($view == 'grid'){echo 'selected="selected"';}?>><?php _e('Grid', 'booking') ?></option>                      <option value="fancy" <?php if($view == 'fancy'){echo 'selected="selected"';}?>><?php _e('Fancy', 'booking') ?></option>                    </select>                  </div>                </div>              </li>            </ul>            <div id="Event-listing<?php echo intval( $cs_counter );?>" >              <ul class="form-elements">                <li class="to-label">                  <label><?php _e('Post Order', 'booking') ?></label>                </li>                <li class="to-field">                  <div class="input-sec">                    <div class="select-style">                      <select name="order[]" class="dropdown" >                        <option <?php if($order=="ASC")echo "selected";?> value="ASC"><?php _e('Asc', 'booking') ?></option>                        <option <?php if($order=="DESC")echo "selected";?> value="DESC"><?php _e('DESC', 'booking') ?></option>                      </select>                    </div>                  </div>                </li>              </ul>              <ul class="form-elements">                <li class="to-label">                  <label><?php _e('Length of Excerpt', 'booking') ?></label>                </li>                <li class="to-field">                  <div class="input-sec">                    <input type="text" name="room_excerpt[]" class="txtfield" value="<?php echo esc_attr($room_excerpt);?>" />                  </div>                  <div class="left-info">                    <p><?php _e('Enter number of character for short description text', 'booking') ?></p>                  </div>                </li>              </ul>            </div>            <ul class="form-elements">              <li class="to-label">                <label><?php _e('Pagination', 'booking') ?></label>              </li>              <li class="to-field select-style">                <select name="rooms_pagination[]" class="dropdown">                  <option <?php if($rooms_pagination=="pagination")echo "selected";?> value="pagination"><?php _e('Pagination', 'booking') ?></option>                  <option <?php if($rooms_pagination=="single_page")echo "selected";?> value="single_page" ><?php _e('Single Page', 'booking') ?></option>                </select>                <div class="left-info">                  <p><?php _e('Paginationn will not work in slider view.', 'booking') ?></p>                </div>              </li>            </ul>            <ul class="form-elements">              <li class="to-label">                <label><?php _e('No. of Post Per Page', 'booking') ?></label>              </li>              <li class="to-field">                <div class="input-sec">                  <input type="text" name="pagination[]" class="txtfield" value="<?php echo esc_attr( $pagination ); ?>" />                </div>                <div class="left-info">                  <p><?php _e('To display all the records, leave this field blank', 'booking') ?></p>                </div>              </li>            </ul>            <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>            <ul class="form-elements insert-bg">              <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo esc_js( str_replace( 'cs_pb_','',$name ) );?>','<?php echo esc_js( $name.$cs_counter );?>','<?php echo esc_js( $filter_element );?>')" ><?php _e('Insert', 'booking') ?></a> </li>            </ul>            <div id="results-shortocde"></div>            <?php } else {?>            <ul class="form-elements">              <li class="to-label"></li>              <li class="to-field">                <input type="hidden" name="cs_orderby[]" value="rooms" />                <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />              </li>            </ul>            <?php }?>          </div>        </div>      </div>    </div><?php		if ( $die <> 1 ) die();	}	add_action('wp_ajax_cs_pb_rooms', 'cs_pb_rooms');}