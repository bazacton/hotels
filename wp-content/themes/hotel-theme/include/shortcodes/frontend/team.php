<?php
/*
 *
 *@Shortcode Name : Teams
 *@retrun
 *
 */
if (!function_exists('cs_teams_shortcode')) {
	function cs_teams_shortcode($atts, $content = "") {
		$defaults = array( 'column_size'=>'1/1','cs_team_section_title' => '','cs_team_name' => '','cs_team_designation' => '','cs_team_title' => '','cs_team_profile_image' => '','cs_team_fb_url' => '','cs_team_twitter_url' => '','cs_team_googleplus_url' => '','cs_team_skype_url' => '','cs_team_email' => '');
		extract( shortcode_atts( $defaults, $atts ) );
		$column_class  = cs_custom_column_class($column_size);

		$html = '';
		$html	.= '<div class="cs-team cs-teamgrid">';
		$html	.= '<article class="col-md-3">';
		$html	.= '<div class="cs-wrapteam">';
		if (isset($cs_team_profile_image) && $cs_team_profile_image !=''){
			$html	.= '<figure>';
			$html	.= '<img alt="'.$cs_team_name.'" src="'. $cs_team_profile_image .'">';
			$html	.= '</figure>';
		}
		
		$html	.= '<div class="cs-text">';
		
		if ( isset( $cs_team_name ) &&  $cs_team_name !='' ) { 
			$html	.= '<h4>'.$cs_team_name.'</h4>';
		}
		
		if ( isset( $cs_team_designation ) &&  $cs_team_designation !='' ) { 
			$html	.= '<span>'.$cs_team_designation.'</span>';
		}
		
		$html	.= '<div class="cs-teaminfo">';
		$html	.= '<div class="cs-seprator"> 
						<div class="devider4">
					  		<span><img alt="img" src="'.get_template_directory_uri().'/assets/images/devider4-img.png"></span>
						</div> 
				    </div>';
					if (isset($content) && $content !=''){
			$html .='<p>'.do_shortcode($content).'</p>';
		}
				
		if ($cs_team_fb_url || $cs_team_twitter_url || $cs_team_googleplus_url || $cs_team_skype_url || $cs_team_email ) { 
			
			$html .= '<div class="social-media"><ul>';
				if (isset($cs_team_fb_url) && $cs_team_fb_url !=''){
					$html .='<li><a href="'.esc_url($cs_team_fb_url).'" target="_blank"><i class="icon-facebook-square"></i></a></li>';
				}
				if (isset($cs_team_twitter_url) && $cs_team_twitter_url !=''){
					$html .='<li><a href="'.esc_url($cs_team_twitter_url).'" target="_blank"><i class="icon-twitter-square"></i></a></li>';
				}
				if (isset($cs_team_googleplus_url) && $cs_team_googleplus_url !=''){
					$html .='<li><a href="'.esc_url($cs_team_googleplus_url).'" target="_blank"><i class="icon-google-plus-square"></i></a></li>';
				}
				if (isset($cs_team_skype_url) && $cs_team_skype_url !=''){
					$html .='<li><a href="'.esc_url($cs_team_skype_url).'" target="_blank"><i class="icon-skype3"></i></a></li>';
				}
				if (isset($cs_team_email) && $cs_team_email !='' && is_email($cs_team_email)){
					$html .='<li><a href="mailto:'.sanitize_email($cs_team_email).'" target="_blank"><i class="icon-envelope7"></i></a></li>';
				}
			$html .='</ul></div>';
		}
			$html	.= '</div>';		  
		
		
	
		
		$html	.= '</div>';
		$html	.= '</div>';
		$html	.= '</article>';
		$html	.= '</div>';

		
		$section_title = '';
		if(trim($cs_team_section_title) <> ''){
			$section_title = '<div class="cs-section-title"><h2>'.$cs_team_section_title.'</h2></div>';
		}
		return $section_title.' '. $html;
	}
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_TEAM, 'cs_teams_shortcode');
    }
}