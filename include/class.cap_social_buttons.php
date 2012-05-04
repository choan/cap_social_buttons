<?php
class CapSocialButtons {

  private static $instance;
  
  private $plugged = array();
  
  private $used_buttons = array();

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
      'width' => '250'),
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
    // do_action('cap_social_buttons_register', $this);
  }

  static public function instance() {
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
    $before  = $this->get_option('buttons_before_content');
    $after   = $this->get_option('buttons_after_content');
    $pre = $post = '';
    if ($before || $after) {
      $buttons = $this->the_buttons();
      if ($before)
        $pre = $buttons;
      if ($after)
        $post = $buttons;
    }
    return "$pre\n$content\n$post";
  }

  public function the_buttons($services = array()) {
    $active_buttons = empty($services) ? $this->get_option('services') : $services;
    $this->used_buttons = array_unique(array_merge($this->used_buttons, $active_buttons));
    $buttons = array_map(array($this, 'the_buttons_callback'), $active_buttons);
    $buttons = join("\n", $buttons);
    if ($wrapper = $this->get_option('wrapper')) {
      $buttons = sprintf($wrapper, $buttons);
    }
    return $buttons;
  }

  public function footer() {
    $footers = array_map(array($this, 'footer_callback'), $this->used_buttons);
    $out = join("\n", $footers);
    print $out;
  }
  
  public function register($name, $callback) {
    $this->plugged[$name] = $callback;
  }
  
  public function addPlugin($class) {
    $plugin = new $class;
    foreach ($plugin->provides as $k => $v) {
      $this->register($k, array($plugin, $v));
    }
  }

  public function get_option($name) {
    static $stored;
    // TODO: tomar opcion de la DB cuando todo esté listo
    $stored || ($stored = get_option('cap_social_buttonsX')) || ($stored = $this->load_default_options());
    if (isset($stored[$name])) {
      return $stored[$name];
    }
    return null;
  }

  private function footer_callback($service) {
    $method = "{$service}_footer";
    if (isset($this->plugged[$method])) {
      return call_user_func($this->plugged[$method], $this);
    }
    else if (method_exists($this, $method)) {
      return $this->$method();
    }
    else {
      return '';
    }
  }

  private function the_buttons_callback($service) {
    $method = "{$service}_button";
    if (isset($this->plugged[$method])) {
      $out = call_user_func($this->plugged[$method], $this);
    }
    else {
      $out = $this->$method();
    }
    return sprintf('<div class="social-buttons-%2$s">%1$s</div>', $out, $service);
  }

  private function load_default_options() {
    // TODO: debe ser add cuando todo esté bien
    update_option('cap_social_buttons', self::$defaults);
    return self::$defaults;
  }

}
