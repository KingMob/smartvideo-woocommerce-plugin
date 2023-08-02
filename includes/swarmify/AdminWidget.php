<?php

namespace SmartvideoWoocommercePlugin\Swarmify;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://swarmify.idevaffiliate.com/idevaffiliate.php?id=10275&url=48
 * @since      1.0.0
 *
 * @package    Swarmify
 * @subpackage Swarmify/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Swarmify
 * @subpackage Swarmify/public
 * @author     Omar Kasem <omar.kasem207@gmail.com>
 */
class AdminWidget extends \WP_Widget {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	// private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	// private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	// public function __construct( $plugin_name = '', $version = '' ) {

	// 	$this->plugin_name = $plugin_name;
	// 	$this->version = $version;
    //     parent::__construct(
    //         'smartvideo_widget',
    //         __('SmartVideo', 'swarmify'),
    //         array('description' => __('SmartVideo Widget', $this->plugin_name),)
    //     );
	// }

	public function __construct(  ) {
		error_log("calling __construct in widget...");

		$widget_ops = [
            'classname'   => 'smartvideo_widget',
            'description' => __( 'SmartVideo Widget', 'swarmify' )
        ];

		error_log("calling parent::__construct in widget...");
        parent::__construct( 'smartvideo_widget', __('SmartVideo Widget', 'swarmify'), $widget_ops);
	}


    // Widgets
    public function widget($args, $instance){
		error_log("calling widget() in widget...");

    	if(empty($instance)){
    		$instance = array(
    			'title' =>'',
    			'swarmify_url'=>'',
    			'swarmify_poster'=>'',
    			'swarmify_autoplay'=>'',
    			'swarmify_muted'=>'',
    			'swarmify_loop'=>'',
    			'swarmify_controls'=>'',
    			'swarmify_video_inline'=>'',
    			'swarmify_unresponsive'=>'',
    			'swarmify_height'=>'',
    			'swarmify_width'=>'',
    		);
    	}
		$cdn_key = get_option('swarmify_cdn_key');
		$swarmify_status = get_option('swarmify_status');
        $title = apply_filters('widget_title', $instance['title']);
        $output = $args['before_widget'];
        if (!empty($title)){
            $output .= $args['before_title'] . $title . $args['after_title'];
        }
        $swarmify_url = $instance['swarmify_url'];

        $swarmify_poster = $instance['swarmify_poster'];
        $swarmify_autoplay = intval($instance['swarmify_autoplay']);
        $swarmify_muted = intval($instance['swarmify_muted']);
        $swarmify_loop = intval($instance['swarmify_loop']);
        $swarmify_controls = intval($instance['swarmify_controls']);
        $swarmify_video_inline = intval($instance['swarmify_video_inline']);
        $swarmify_unresponsive = intval($instance['swarmify_unresponsive']);
        $swarmify_height = intval($instance['swarmify_height']);
        $swarmify_width = intval($instance['swarmify_width']);
        $errors = array();
        if($cdn_key === ''){
        	$errors[] = 'CDN Key field is required.';
        }
        if($swarmify_status !== 'on'){
        	$errors[] = 'SmartVideo is disabled.';
        }

        if($swarmify_url === ''){
        	$errors[] = 'The Video URL is required.';
        }

        if(empty($errors)){
        	if(!empty($swarmify_poster)){
        		$poster = 'poster="'.$swarmify_poster.'"';
        	}else{
        		$poster = '';
        	}

        	$autoplay = ($swarmify_autoplay === 1 ? 'autoplay' : '');
        	$muted = ($swarmify_muted === 1 ? 'muted' : '');
        	$loop = ($swarmify_loop === 1 ? 'loop' : '');
        	$controls = ($swarmify_controls === 1 ? 'controls' : '');
        	$video_inline = ($swarmify_video_inline === 1 ? 'playsinline' : '');
        	$unresponsive = ($swarmify_unresponsive === 1 ? 'class="swarm-fluid"' : '' );

        	$output .= '<smartvideo src="'.$swarmify_url.'" width="'.$swarmify_width.'" height="'.$swarmify_height.'" '.$unresponsive.' poster="'.$swarmify_poster.'" '.$autoplay.' '.$muted.' '.$loop.' '.$controls.' '.$video_inline.'></smartvideo>';
        }else{
        	$output .= '<ul>';
        	foreach($errors as $error){
        		$output .= '<li>'.$error.'</li>';
        	}
        	$output .= '</ul>';
        }
        $output.= $args['after_widget'];
        
        $output = str_replace('et_pb_widget', '', $output);
		echo $output;
    }

    public function form($instance){
		error_log("calling form() in widget...");
    	$title = isset($instance['title']) ? $instance['title'] : '';
    	$page = isset($instance['page']) ? $instance['page'] : '';
    	require('partials/swarmify-widget-display.php');
	}


    public function update($new_instance, $old_instance){
		error_log("calling update() in widget...");

    	$instance = array();
    	$instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
    	$instance['swarmify_url'] = !empty($new_instance['swarmify_url']) ? $new_instance['swarmify_url'] : '';
    	$instance['swarmify_poster'] = !empty($new_instance['swarmify_poster']) ? $new_instance['swarmify_poster'] : '';
    	$instance['swarmify_autoplay'] = !empty($new_instance['swarmify_autoplay']) ? intval($new_instance['swarmify_autoplay']) : 0;
    	$instance['swarmify_muted'] = !empty($new_instance['swarmify_muted']) ? intval($new_instance['swarmify_muted']) : 0;
    	$instance['swarmify_loop'] = !empty($new_instance['swarmify_loop']) ? intval($new_instance['swarmify_loop']) : 0;
    	$instance['swarmify_controls'] = !empty($new_instance['swarmify_controls']) ? intval($new_instance['swarmify_controls']) : 0;
    	$instance['swarmify_height'] = !empty($new_instance['swarmify_height']) ? intval($new_instance['swarmify_height']) : 720;
    	$instance['swarmify_width'] = !empty($new_instance['swarmify_width']) ? intval($new_instance['swarmify_width']) : 1280;

    	if(in_array('swarmify_controls',$old_instance) && $old_instance['swarmify_controls'] === null){
    		$instance['swarmify_controls'] = 1;
    	}
    	$instance['swarmify_video_inline'] = !empty($new_instance['swarmify_video_inline']) ? intval($new_instance['swarmify_video_inline']) : 0;
    	$instance['swarmify_unresponsive'] = !empty($new_instance['swarmify_unresponsive']) ? intval($new_instance['swarmify_unresponsive']) : 0;
    	if(in_array('swarmify_unresponsive',$old_instance) && $old_instance['swarmify_unresponsive'] === null){
    		$instance['swarmify_unresponsive'] = 1;
    	}
    	return $instance;
    }


}
