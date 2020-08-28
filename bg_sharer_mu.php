<?php
/*
*
* @package yariko


Plugin Name:  MU Sharer
Plugin URI:   https://thomasgbennett.com/
Description:  Create a basic flow for promote content approval top parent website (This plugin require broadcast plugin)
Version:      1.0.0
Author:       Bennet Group (Yariel Gordillo)
Author URI:   https://thomasgbennett.com/
Tested up to: 5.3.2
Text Domain:  bg_sharer_mu
Domain Path:  /languages
*/

defined('ABSPATH') or die('You do not have access, sally human!!!');

define ( 'BGS_PLUGIN_VERSION', '1.0.0');

if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php') ){
    require_once  dirname( __FILE__ ) . '/vendor/autoload.php';
}
//Change WRPL for plugin's initials
define('BGS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define('BGS_PLUGIN_URL' , plugin_dir_url(  __FILE__  ) );
define('BGS_ADMIN_URL' , get_admin_url() );
define('BGS_PLUGIN_DIR_BASENAME' , dirname(plugin_basename(__FILE__)) );

//include the helpers
include 'inc/util/helpers.php';

if( class_exists( 'Bgs\\Inc\\Init' ) ){
    register_activation_hook( __FILE__ , array('Bgs\\Inc\\Base\\Activate','activate') );
    //Set a new role for user that are expecting approval
    //Ask for the existence of pending role
    add_role(
        'pending_contributor',
        __( 'Pending Contributor'),
        array()
    );
    Bgs\Inc\Init::register_services();
}



