<?php

class CapSocialButtons_Twitter {
  
  var $provides = array(
    'twitter_share_button' => 'button',
    'twitter_share_footer' => 'footer');
  
  public function button($caller) {
    global $post;
    $options = $caller->get_option('twitter_share');
    $url = get_permalink();
    $title = get_the_title();
    $lang = substr(WPLANG, 0, 2);
    $text = str_replace('%title%', $title, $options['text']);
    $out = '<a href="https://twitter.com/share" class="twitter-share-button" ';
    $out .= " data-url='{$url}' ";
    $out .= " data-text='{$text}' ";
    $out .= " data-via='{$options['via']}' ";
    $out .= " data-lang='{$lang}' ";
    $out .= '>Tweet</a>';
    return $out;    
  }
  
  public function footer($caller) {
    $out = '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
    return $out;    
  }
  
}

