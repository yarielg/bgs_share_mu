<?php

/*
*
* @package yariko
*
*/
namespace Bgs\Inc\Base;

class Activate{

    public static function activate(){
        global $wpdb;
        //DB run once
        if(bgs_get_option_parent('bgs_db_setup') != 1){

            $charset_collate = $wpdb->get_charset_collate();
            $table_name = $wpdb->base_prefix . 'wrn_resources';
            $sql = "CREATE TABLE $table_name (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              name varchar(100) NOT NULL,
              url varchar(300) NOT NULL,
              status varchar(11) NOT NULL,
              admins varchar(300) NOT NULL,
              blog_id INT(10) NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
            bgs_update_option_parent('bgs_db_setup', 1);
            bgs_update_option_parent('bgs_max_admin_approval', 1);
        }

        //Set a new role for user that are expecting approval
        //Ask for the existence of pending role
        add_role(
            'pending_contributor',
            __( 'Pending Contributor'),
            array()
        );
        $role_object = get_role( 'contributor' );
        $role_object->remove_cap( 'publish_posts' );
        $role_object->add_cap( 'publish_resources' );
    }
}
