<?php
/*
Plugin Name: Cap Social Buttons
Plugin URI: http://choangalvez.nom.es/
Description: Simple plugins to output social sharing buttons
Author: Choan Gálvez
Version: 0.0.1
Author URI: http://choangalvez.nom.es/
*/

define('CAP_SOCIAL_BUTTONS_PATH', dirname(__FILE__));
define('CAP_SOCIAL_BUTTONS_INCLUDE_PATH', CAP_SOCIAL_BUTTONS_PATH . '/include');
define('CAP_SOCIAL_BUTTONS_FILE', __FILE__);

require CAP_SOCIAL_BUTTONS_INCLUDE_PATH . '/tags.php';
require CAP_SOCIAL_BUTTONS_INCLUDE_PATH . '/class.cap_social_buttons.php';
require CAP_SOCIAL_BUTTONS_INCLUDE_PATH . '/class.cap_social_buttons_facebook.php';
require CAP_SOCIAL_BUTTONS_INCLUDE_PATH . '/class.cap_social_buttons_twitter.php';
require CAP_SOCIAL_BUTTONS_INCLUDE_PATH . '/class.cap_social_buttons_plus_one.php';

add_action('cap_social_buttons_register', array(new CapSocialButtons_Facebook, 'register'));
add_action('cap_social_buttons_register', array(new CapSocialButtons_Twitter, 'register'));
add_action('cap_social_buttons_register', array(new CapSocialButtons_PlusOne, 'register'));


add_filter('the_content', array(CapSocialButtons::instance(), 'the_content'));
add_action('wp_footer', array(CapSocialButtons::instance(), 'footer'));
add_action('wp_enqueue_scripts', array(CapSocialButtons::instance(), 'enqueue_scripts'));
