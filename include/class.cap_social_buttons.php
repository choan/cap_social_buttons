<?php
class CapSocialButtons {

  private static $instance;

  static $defaults = array(
    'services' => array(
      'facebook_like',
      'twitter_share',
      'plus_one'),
    'wrapper' => '<div class="cap-social-buttons">%s</div>',
    'buttons_before_content' => false,
    'buttons_after_content' => false,
    'facebook_like' => array(
      'action' => 'like',
      'send' => true,
      'layout' => 'button_count',
      'show_faces' => false,
      'font' => 'arial',
      'colorscheme' => 'light',
      'ref' => null,
      'implementation' => 'xfbml',
      'width' => '120'),
    'twitter_share' => array(
      'via' => 'ukexin',
      'text' => '%title%'),
    'plus_one' => array(
      'size' => 'medium',
      'annotation' => 'bubble',
      'implementation' => 'xml'));


  static function bool_to_s($b) {
    return $b ? 'true' : 'false';
  }


  public function __construct() {
  }

  public function instance() {
    if (!self::$instance) {
      $name = __CLASS__;
      self::$instance = new $name;
    }
    return self::$instance;
  }

  public function enqueue_scripts() {
    wp_register_style(
        'cap_social_buttons.css',
        plugins_url(basename(dirname(CAP_SOCIAL_BUTTONS_FILE))) . '/css/cap_social_buttons.css',
        false,
        0.1
    );
    wp_enqueue_style('cap_social_buttons.css');
  }

  public function the_content($content) {
    $buttons = $this->the_buttons();
    $pre = $post = '';
    if ($this->get_option('buttons_before_content'))
      $pre = $buttons;
    if ($this->get_option('buttons_after_content'))
      $post = $buttons;
    return "$pre\n$content\n$post";

  }

  public function the_buttons($services = array()) {
    $active_buttons = empty($services) ? $this->get_option('services') : $services;
    $buttons = array_map(array($this, 'the_buttons_callback'), $active_buttons);
    $buttons = join("\n", $buttons);
    if ($wrapper = $this->get_option('wrapper')) {
      $buttons = sprintf($wrapper, $buttons);
    }
    return $buttons;
  }

  public function footer() {
    $footers = array_map(array($this, 'footer_callback'), $this->get_option('services'));
    $out = join("\n", $footers);
    print $out;
  }

  private function get_option($name) {
    static $stored;
    // TODO: tomar opcion de la DB cuando todo esté listo
    $stored || ($stored = get_option('cap_social_buttonsX')) || ($stored = $this->load_default_options());
    if (isset($stored[$name])) {
      return $stored[$name];
    }
    return null;
  }

  private function facebook_like_button() {
    global $post;
    $options = $this->get_option('facebook_like');
    $url = get_permalink();
    if ($options['implementation'] == 'html5') {
      $template = '<div class="fb-like" data-href="%1$s" data-send="%2$s" data-layout="%3$s" data-width="%4$s" data-show-faces="%5$s"></div>';
    }
    else {
      $template = '<fb:like href="%1$s" send="%2$s" layout="%3$s" width="%4$s" show_faces="%5$s"></fb:like>';
    }

    if ($options['send'] && $options['layout'] == 'button_count') {
      $options['send'] = false;
    }

    $buttons = sprintf($template, $url, self::bool_to_s($options['send']), $options['layout'], $options['width'], self::bool_to_s($options['show_faces']));
    return $buttons;
  }

  private function facebook_like_footer() {
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

  private function twitter_share_button() {
    global $post;
    $options = $this->get_option('twitter_share');
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

  private function twitter_share_footer() {
    $out = '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
    return $out;
  }

  private function plus_one_button() {
    global $post;
    $options = $this->get_option('plus_one');
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

  private function plus_one_footer() {
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


  private function footer_callback($service) {
    $method = "{$service}_footer";
    if (method_exists($this, $method)) {
      return $this->$method();
    }
    else {
      return '';
    }
  }

  private function the_buttons_callback($service) {
    $method = "{$service}_button";
    return sprintf('<div class="social-buttons-%2$s">%1$s</div>', $this->$method(), $service);
  }

  private function load_default_options() {
    // TODO: debe ser add cuando todo esté bien
    update_option('cap_social_buttons', self::$defaults);
    return self::$defaults;
  }

}
