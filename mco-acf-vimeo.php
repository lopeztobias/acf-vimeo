<?php

/**
 * Minimalart - ACF Vimeo
 *
 * Plugin Name:       MinimalArt - ACF Vimeo
 * Plugin URI:        minimalart.co
 * Description:       Customize WordPress with powerful, professional and intuitive fields.
 * Version:           1.0.0
 * Author:            Minimalart
 * Author URI:        minimalart.co
 * Text Domain:       mco-acf-vimeo
 * Requires PHP:      7.0
 * Requires at least: 5.8
 */

if (!defined('ABSPATH')) {
   exit; // Exit if accessed directly.
}

define("MCO_ACF_VIMEO_DIR", plugin_dir_path(__FILE__));
define("MCO_ACF_VIMEO_URI", plugin_dir_url(__FILE__));
define("MCO_ACF_VIMEO_VERSION", '1.0.0');

require MCO_ACF_VIMEO_DIR . "/acf-vimeo-uploader---minimalart/init.php";

if(!class_exists('McoACFVimeo')){
   require MCO_ACF_VIMEO_DIR . "/mco-acf-vimeo-class.php";
}

new McoACFVimeo(__FILE__);