<?php

/*
*
* @package Yariko
*
*/

namespace Bgs\Inc\Base;

class Pages{

    public function register(){

        //Set a new page under Media menu in Parent Dashboard Website
        add_action('admin_menu', array($this,'bgs_add_request_page'));

    }

    function bgs_add_request_page(){
        $user = wp_get_current_user();
        if(get_current_blog_id() == 1  && in_array( 'administrator', $user->roles )){

            add_submenu_page( 'edit.php?post_type=bgs_resources', 'Requests', 'Requests', 'manage_options', 'bgs_requests', function(){
                $myListTable = new \Request_Resource_Table();
                echo '<div class="wrap"><h2>Requests</h2>';
                $myListTable->prepare_items();
                $myListTable->display();
                echo '</div>';
            });


            add_submenu_page( 'edit.php?post_type=bgs_resources', 'Settings', 'Settings', 'manage_options', 'bgs_settings', function(){
                if(isset($_POST['max_admin_approval_submit'])){
                    if($_POST['max_admin_approval'] > 0){
                        bgs_update_option_parent('bgs_max_admin_approval',$_POST['max_admin_approval']);
                    }else{
                        echo '<div class="error notice"><p>Error, No negative number are allowed</p></div>';
                    }
                }
                echo '<div class="wrap"><h2>Settings</h2>';
                echo '<br>';
                echo '<hr>';
                echo '<br>';
                echo '<form action="edit.php?post_type=bgs_resources&page=bgs_settings" method="post">';
                echo '<label for="max_admin_approval"> Define how many admins should approve a resource before submitting it to the parent site  </label>';
                echo '<input id="max_admin_approval" type="number" name="max_admin_approval" value="'.bgs_get_option_parent('bgs_max_admin_approval').'">';
                echo '<br><br><br>';
                echo '<input id="max_admin_approval_submit" min=0 type="submit" name="max_admin_approval_submit" value="Update Settings">';
                echo '</form>';
                echo '</div>';
            } );
        }
    }
}
?>