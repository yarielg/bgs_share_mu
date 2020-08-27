<?php

/*
*
* @package Yariko
*
*/

namespace Bgs\Inc\Base;

class Enqueue{

    public function register(){

        /*add_action( 'admin_enqueue_scripts', array( $this , 'Wrkswp_enqueue_admin' ) ); //action to include script to the backend, in order to include in the frontend is just wp_enqueue_scripts instead admin_enqueue_scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'Wrkswp_enqueue_frontend'));

        add_action('plugins_loaded', array($this,'Wrkswp_translate_plugin'));*/


    }

   /* function Wrkswp_translate_plugin() {
        load_plugin_textdomain( 'bg_sharer_mu', false, WRKSWP_PLUGIN_DIR_BASENAME .'/languages/' );
    }

    function Wrkswp_enqueue_frontend(){
        //enqueue all our scripts frontend
    }

    function Wrkswp_enqueue_admin(){
        //enqueue all our scripts admin
        //wp_enqueue_style( 'bootstrap_css', WRKS_PLUGIN_URL . '/assets/css/admin/bootstrap.min.css'  );

    }*/

}