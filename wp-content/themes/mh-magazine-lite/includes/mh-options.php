<?php

/***** Settings *****/

function mh_customize_register($wp_customize) {

	/***** Register Custom Controls *****/
	
	class MH_Customize_Textarea_Control extends WP_Customize_Control {
    	public $type = 'textarea';
    	public function render_content() {
        	?>
            <label>
                <span class="customize-textarea"><?php echo esc_html($this->label); ?></span>
                <textarea rows="5" style="width: 100%;" <?php $this->link(); ?>><?php echo esc_textarea($this->value()); ?></textarea>
            </label>
            <?php
	    }
	}

	/***** Add Sections *****/

	$wp_customize->add_section('mh_general', array('title' => __('General Options', 'mh'), 'priority' => 1));
   
    /***** Add Settings *****/
    
    $wp_customize->add_setting('mh_options[full_bg]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_checkbox')); 
    $wp_customize->add_setting('mh_options[comments_pages]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_checkbox'));   
    $wp_customize->add_setting('mh_options[sb_position]', array('default' => 'right', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_select'));
    $wp_customize->add_setting('mh_options[excerpt_length]', array('default' => '45', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_integer'));   
    $wp_customize->add_setting('mh_options[custom_css]', array('default' => '', 'type' => 'option'));
    $wp_customize->add_setting('mh_options[tracking_code]', array('default' => '', 'type' => 'option'));
    
    /***** Add Controls *****/
        
    $wp_customize->add_control('full_bg', array('label' => __('Scale background image to full size', 'mh'), 'section' => 'mh_general', 'settings' => 'mh_options[full_bg]', 'priority' => 11, 'type' => 'checkbox'));    
    $wp_customize->add_control('comments_pages', array('label' => __('Disable comments on pages', 'mh'), 'section' => 'mh_general', 'settings' => 'mh_options[comments_pages]', 'priority' => 12, 'type' => 'checkbox'));    
    $wp_customize->add_control('sb_position', array('label' => __('Default position of sidebar', 'mh'), 'section' => 'mh_general', 'settings' => 'mh_options[sb_position]', 'priority' => 13, 'type' => 'select', 'choices' => array('left' => __('Left', 'mh'), 'right' => __('Right', 'mh'))));
    $wp_customize->add_control('excerpt_length', array('label' => __('Custom excerpt length (Words)', 'mh'), 'section' => 'mh_general', 'settings' => 'mh_options[excerpt_length]', 'priority' => 14, 'type' => 'text'));   
    $wp_customize->add_control(new MH_Customize_Textarea_Control($wp_customize, 'custom_css', array('label' => __('Custom CSS', 'mh'), 'section' => 'mh_general', 'settings' => 'mh_options[custom_css]', 'priority' => 15)));
    $wp_customize->add_control(new MH_Customize_Textarea_Control($wp_customize, 'tracking_code', array('label' => __('Tracking Code (e.g. Google Analytics)', 'mh'), 'section' => 'mh_general', 'settings' => 'mh_options[tracking_code]', 'priority' => 16)));
}
add_action('customize_register', 'mh_customize_register');

/***** Data Sanitization *****/

function mh_sanitize_integer($input) {
    return strip_tags($input);
}
function mh_sanitize_checkbox($input) {
    if ($input == 1) {
        return 1;
    } else {
        return '';
    }
}
function mh_sanitize_select($input) {
    $valid = array(
        'left' => __('Left', 'mh'),
        'right' => __('Right', 'mh')
    ); 
    if (array_key_exists($input, $valid)) {
        return $input;
    } else {
        return '';
    }
}

/***** Output *****/

function mh_custom_css() {
	$options = get_option('mh_options');    
	if ($options['custom_css']) {	
    	echo '<style type="text/css">' . "\n";
			echo $options['custom_css'] . "\n";
		echo '</style>' . "\n";
	}
}
add_action('wp_head', 'mh_custom_css');

?>