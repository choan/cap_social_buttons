<?php 
/*
Plugin Name: Social Buttons
Plugin URI: http://choangalvez.nom.es/
Description: Simple plugins to output social sharing buttons
Author: Choan Gálvez
Version: 0.0.1
Author URI: http://choangalvez.nom.es/
*/

define('SOCIAL_BUTTONS_PATH', dirname(__FILE__));
define('SOCIAL_BUTTONS_INCLUDE_PATH', SOCIAL_BUTTONS_PATH . '/include');
define('SOCIAL_BUTTONS_FILE', __FILE__);

require SOCIAL_BUTTONS_INCLUDE_PATH . '/tags.php';
require SOCIAL_BUTTONS_INCLUDE_PATH . '/class.social_buttons.php';


add_filter('the_content', array(SocialButtons::instance(), 'the_content'));
add_action('wp_footer', array(SocialButtons::instance(), 'footer'));
add_action('wp_enqueue_scripts', array(SocialButtons::instance(), 'enqueue_scripts'));
