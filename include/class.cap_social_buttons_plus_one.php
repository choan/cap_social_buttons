<?php

class CapSocialButtons_PlusOne {
  
  var $provides = array(
    'plus_one_button' => 'button',
    'plus_one_footer' => 'footer');
  
  public function button($caller) {
    global $post;
    $options = $caller->get_option('plus_one');
    $url = get_permalink();
    if ($options['implementation'] == 'html5') {
      $template = '<div class="g-plusone" data-size="%1$s" data-annotation="%2$s" data-href="%3$s"></div>';
    }
    else {
      $template = '<g:plusone size="%1$s" annotation="%2$s" href="%3$s"></g:plusone>';
    }

    $buttons = sprintf($template, $options['size'], $options['annotation'], $url);
    return $buttons;  
  }
  
  public function footer($caller) {
    $lang = substr(0, 2, WPLANG);
    return "<script type='text/javascript'>
      window.___gcfg = {lang: '{$lang}'};

      (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
      })();
    </script>";
  }
  
}