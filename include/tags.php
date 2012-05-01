<?php

function social_buttons_the_buttons($services = array()) {
  print SocialButtons::instance()->the_buttons($services);
}