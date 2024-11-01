<?php
/*
Plugin Name: Zuppler Online Ordering
Plugin URI: http://api.zuppler.com/docs/wordpress-plugin.html
Description: This plugin lets you easily integrate Zuppler Online Ordering.
Author: Zuppler Dev Team
Author URI: http://zupplerworks.com/
Version: 2.1.0
*/

/*  Copyright 2012 Zuppler Dev Team

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

require_once('inc/Mustache.php');
class Zuppler_integration {
  var $load_menu_assets = false;
  var $zupplerhost = "http://api.zuppler.com/v3";
  var $scripthost = "//web4.zuppler.com";
  var $plugin_url;

  var $channel_slug;
  var $integration_type; // 0 = restaurant; 1 = portal
  var $restaurant_slug;
  var $restaurant_id;
  var $locale;

  var $colors_background;
  var $colors_highContrast;
  var $colors_midContrast;
  var $colors_lowContrast;
  var $colors_brand;
  var $colors_heroBackground;
  var $colors_heroContrast;
  var $fonts_heading;
  var $fonts_body;
  var $fonts_deco;

  // network vars
  var $load_network_assets = false;
  // var $check_is_restaurant_open = false;
  var $script_is_restaurant_open = array();
  var $channel = array();

  var $expiration = 3600; // in seconds

  function __construct() {
    $this->channel_slug     = get_option('zuppler_channel_slug');
  	$this->integration_type = get_option('zuppler_integration_type');
    $this->restaurant_slug  = get_option('zuppler_restaurant_slug');
    $this->restaurant_id  = get_option('zuppler_restaurant_id');
    $this->locale  = get_option('zuppler_locale');
    
    $this->colors_background = get_option('zuppler_colors_background');
    $this->colors_highContrast = get_option('zuppler_colors_highContrast');
    $this->colors_midContrast = get_option('zuppler_colors_midContrast');
    $this->colors_lowContrast = get_option('zuppler_colors_lowContrast');
    $this->colors_brand = get_option('zuppler_colors_brand');
    $this->colors_heroBackground = get_option('zuppler_colors_heroBackground');
    $this->colors_heroContrast = get_option('zuppler_colors_heroContrast');
    $this->fonts_heading = get_option('zuppler_fonts_heading');
    $this->fonts_body = get_option('zuppler_fonts_body');
    $this->fonts_deco = get_option('zuppler_fonts_deco');

  	$file = dirname(__FILE__) . '/zuppler-online-ordering.php';
    $this->plugin_url = plugin_dir_url($file);

	  add_shortcode('zuppler',  array(&$this, 'zuppler_shortcodes') );
	  add_filter('widget_text',  'do_shortcode');
    add_action('wp_head', array(&$this, 'prefetch_zuppler_scripts'), 11);
  	add_action('wp_footer', array(&$this, 'enqueue_scripts'), 11);
  }

  function zuppler_shortcodes($atts, $content = null) {

    $extra_attributes = "";
    $restaurant_id = $this->restaurant_id;
    $restaurant_slug = $this->restaurant_slug;
  
    foreach((array) $atts as $key => $val) {
      
      switch($key) {
        case "options":
          $extra_attributes = $val;
          break;
        case "restaurant":
          $restaurant_slug = $val;
          break;
        case "id":
          $restaurant_id = $val;
          break;
      }
      
    }
    
    $restaurant_attributes = ($this->integration_type == 0) ? "data-integration='{$restaurant_slug}' data-restaurant-id='{$restaurant_id}'" : "";

    $channel_attributes = ($this->integration_type == 1) ? "data-locale='{$this->locale}'" : "";
    
    $tmpl = 
<<<EOT
    <div id="zuppler-menu"
      data-channel-url="http://api.zuppler.com/v3/channels/$this->channel_slug.json"
      $channel_attributes
      $restaurant_attributes
      $extra_attributes
      data-colors-background="$this->colors_background"
      data-colors-highContrast="$this->colors_highContrast"
      data-colors-midContrast="$this->colors_midContrast"
      data-colors-lowContrast="$this->colors_lowContrast"
      data-colors-brand="$this->colors_brand"
      data-colors-heroBackground="$this->colors_heroBackground"
      data-colors-heroContrast="$this->colors_heroContrast"
      data-fonts-heading="$this->fonts_heading"
      data-fonts-body="$this->fonts_body"
      data-fonts-deco="$this->fonts_deco">
        Loading menu data! Please stand by!
      </div>
EOT;

    $this->load_menu_assets = true;
    return $tmpl;
  
  } // END zuppler_shortcodes

  function prefetch_zuppler_scripts() {
    if(!$this->load_menu_assets) {
      $script = ($this->integration_type == 1) ? "portal.js" : "order.js";
      $output = "<link rel='prefetch' href='//web4.zuppler.com/common.js'/>";
      $output .= "<link rel='prefetch' href='//web4.zuppler.com/{$script}'/>";
      echo $output;
    }
  }

  function enqueue_scripts() {

    if($this->load_menu_assets) {
      $script = ($this->integration_type == 1) ? "portal.js" : "order.js";
      
      wp_register_script(
        "zuppler-online-ordering-common",
        "{$this->scripthost}/common.js",
        false,
        null,
        true);
      wp_enqueue_script( 'zuppler-online-ordering-common' );
      
      wp_register_script(
        "zuppler-online-ordering-script",
        "{$this->scripthost}/{$script}",
        false,
        null,
        true);
      wp_enqueue_script( 'zuppler-online-ordering-script' );
    }

  }

  function get_resource($resource_url, $transient){
    $resource = get_transient( $this->channel_slug . $transient );
    if($resource === false) {
      $response = wp_remote_get($resource_url, array( 'User-Agent' => 'WordPress Zuppler Plugin' ));
      $response_code = wp_remote_retrieve_response_code( $response );
      if($response_code == 200) {
        $resource = wp_remote_retrieve_body( $response );
        set_transient( $transient, $resource, $this->expiration );
      } else {
        return false;
      }
    }
    return json_decode( $resource, true );
  }

} /* END Zuppler_integration */


new Zuppler_integration;


function zuppler_online_ordering_admin() {
	include('zuppler-online-ordering-admin.php');
}
function zuppler_online_ordering_admin_actions() {
    add_menu_page('Zuppler Online Ordering Options', 'Zuppler Online Ordering', "edit_posts", "zuppler-online-ordering-options", "zuppler_online_ordering_admin", plugin_dir_url(__FILE__) . "images/zuppler-icon-16px.png");
}
add_action('admin_menu', 'zuppler_online_ordering_admin_actions');

function admin_register_head() {
  $styles_url = plugins_url() . "/zuppler-online-ordering/stylesheets/admin.css";
  wp_register_style('zuppler-admin-styles', $styles_url);
  wp_enqueue_style( 'zuppler-admin-styles');
}
add_action('admin_init', 'admin_register_head');


?>
