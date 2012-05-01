<?php

function cap_social_buttons($services = array()) {
  print CapSocialButtons::instance()->the_buttons($services);
}