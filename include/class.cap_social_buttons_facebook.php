<?php

class CapSocialButtons_Facebook{
    
  var $provides = array(
    'facebook_like_button' => 'button',
    'facebook_like_footer' => 'footer');
        
  public function button($caller) {
    global $post;
    $options = $caller->get_option('facebook_like');
    $url = get_permalink();
    if ($options['implementation'] == 'html5') {
      $template = '<div class="fb-like" data-href="%1$s" data-send="%2$s" data-layout="%3$s" data-width="%4$s" data-show-faces="%5$s"></div>';
    }
    else {
      $template = '<fb:like href="%1$s" send="%2$s" layout="%3$s" width="%4$s" show_faces="%5$s"></fb:like>';
    }

    $buttons = sprintf($template, $url, CapSocialButtons::bool_to_s($options['send']), $options['layout'], $options['width'], CapSocialButtons::bool_to_s($options['show_faces']));
    return $buttons;
  }
  
  public function footer($caller) {
    $lang = WPLANG;
    $out = '<div id="fb-root"></div>';
    // TODO: app_id?
    $out .= "<script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = '//connect.facebook.net/{$lang}/all.js#xfbml=1';
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>";
    return $out;
  }
  
  
}
