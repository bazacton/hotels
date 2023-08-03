<?php
/*
 *
 *@Shortcode Name : Tweets
 *@retrun
 *
 */

if (!function_exists('cs_tweets_shortcode')) {

    function cs_tweets_shortcode($atts, $content = "") {
        $defaults = array('column_size'=>'','cs_tweets_section_title' => '','cs_tweets_user_name' => 'default','cs_tweets_color' => '','cs_no_of_tweets' => '','cs_tweets_class' => '');
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        
        $CustomId    = '';
        if ( isset( $cs_tweets_class ) && $cs_tweets_class ) {
            $CustomId    = 'id="'.$cs_tweets_class.'"';
        }
        
        $rand_id = rand(5, 999999);
        $html = '';
        $section_title = '';
        //$html .= '<div '.$CustomId.' class="twitter-section col-md-12 '.$cs_tweets_class.'" >';
        //$html .= "<div class='twitter_widget'><div class='flexslider cs-twitter-slider flexslider".$rand_id."'>";
		//$html .= "<div class='flex-viewport'><ul class='slides'>";
        $html .= cs_get_tweets($cs_tweets_user_name,$cs_no_of_tweets,$cs_tweets_color , $CustomId , $cs_tweets_class);
        /* $html.='</ul></div></div></div></div>';
        cs_enqueue_flexslider_script();
                $html.='<script type="text/javascript">
                        jQuery(document).ready(function(){
                            jQuery(".twitter_widget .flexslider'.intval($rand_id).'").flexslider({
                                animation: "fade",
                                slideshow: true,
                                slideshowSpeed: 7000,
                                animationDuration: 600,
                                prevText:"<em class=\'icon-angle-up\'></em>",
                                nextText:"<em class=\'icon-angle-down\'></em>",
                                start: function(slider) {
                                    jQuery(".flexslider").fadeIn();
                                }
                            });
                        });
                    </script>'; 
					*/
        return $html;
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_TWEETS, 'cs_tweets_shortcode');
    }
}

/*
 *
 *@Get Tweets
 *@retrun
 *
 */
if (!function_exists('cs_get_tweets')) {
function cs_get_tweets($username,$numoftweets,$cs_tweets_color = '' ,$CustomId , $cs_tweets_class){
            global $cs_theme_options;
            
            $username = html_entity_decode($username);
             $numoftweets = $numoftweets;        
             if($numoftweets == ''){ $numoftweets = 2;}
            if(strlen($username) > 1){
				
				
                	$cs_twitter_api_switch = isset($cs_theme_options['cs_twitter_api_switch']) ? $cs_theme_options['cs_twitter_api_switch'] : '';
					$cs_twitter_arg['consumerkey'] = isset($cs_theme_options['cs_consumer_key']) ? $cs_theme_options['cs_consumer_key'] : '';
					$cs_twitter_arg['consumersecret'] = isset($cs_theme_options['cs_consumer_secret']) ? $cs_theme_options['cs_consumer_secret'] : '';
					$cs_twitter_arg['accesstoken'] = isset($cs_theme_options['cs_access_token']) ? $cs_theme_options['cs_access_token'] : '';
					$cs_twitter_arg['accesstokensecret'] = isset($cs_theme_options['cs_access_token_secret']) ? $cs_theme_options['cs_access_token_secret'] : '';
					$cs_cache_limit_time = isset($cs_theme_options['cs_cache_limit_time']) ? $cs_theme_options['cs_cache_limit_time']: '';
					$cs_tweet_num_from_twitter = isset($cs_theme_options['cs_tweet_num_post']) ? $cs_theme_options['cs_tweet_num_post'] : '';
					$cs_twitter_datetime_formate = isset($cs_theme_options['cs_twitter_datetime_formate']) ? $cs_theme_options['cs_twitter_datetime_formate'] : '';
					//print_r($cs_twitter_arg);
					if($cs_twitter_api_switch == 'on')
					{
						if($cs_twitter_arg['consumerkey'] <> '' && $cs_twitter_arg['consumersecret'] <> '' &&  $cs_twitter_arg['accesstoken'] <> '' && $cs_twitter_arg['accesstokensecret'] <> '')
						{
							//require_once get_template_directory() . '/include/theme-components/cs-twitter/display-tweets.php';

							display_tweets_shortcode($username, $CustomId , $cs_tweets_class ,$cs_twitter_datetime_formate , $cs_tweet_num_from_twitter, $numoftweets, $cs_cache_limit_time ,$cs_tweets_color);	
						}
						else
						{
							echo '<p>'.__('Please Set Twitter API','luxury-hotel').'</p>';
						}
						
					}
			 
        } 
  } 
}
?>