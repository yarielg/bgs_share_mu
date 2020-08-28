<?php

    /**
     * @param $id
     * @return int
     * Get the number of admin who have signed the approval
     */
    function bgs_get_num_admin_signed($id){
        $admins = get_post_meta($id,'admin_signed',true) ? unserialize(get_post_meta($id,'admin_signed',true)) : array();
        return is_array($admins )  ? count($admins) : 0;
    }

    /**
     * @param $id
     * @return array|mixed
     * Get the number of administrators who have signed the approval
     */
    function bgs_get_admin_signed($id){
        return get_post_meta($id,'admin_signed',true) ? unserialize(get_post_meta($id,'admin_signed',true)) : array();
    }

    /**
     * @param $option_name
     * @return bool|mixed
     * Create my own get_option but another level (get_site and get_network not working properly)
     */
    function bgs_get_option_parent($option_name){
        global $wpdb;
        $options = $wpdb->get_results("SELECT * FROM $wpdb->base_prefix" . "options WHERE option_name='{$option_name}'", ARRAY_A);
        if(count($options)>0){
            return $options[0]['option_value'];
        }
        return false;
    }

    /**
     * @param $option_name
     * @param $option_value
     * @return bool
     * Create my own update_option but another level
     */
    function bgs_update_option_parent($option_name,$option_value){
        global $wpdb;
        if(bgs_get_option_parent($option_name)){
            $wpdb->query("UPDATE $wpdb->base_prefix" . "options SET option_value='$option_value' WHERE option_name='$option_name'");
            return true;
        }else{
            $wpdb->query("INSERT INTO $wpdb->base_prefix" . "options (option_name,option_value) VALUES ('$option_name','$option_value')");
            if($wpdb->insert_id > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    //get all the requested resources
    function bgs_get_all_requested_resources()
    {
        global $wpdb;
        $resources = $wpdb->get_results("SELECT * FROM $wpdb->base_prefix" . "wrn_resources", ARRAY_A);
        return $resources;
    }

    //Check is the media exists
    function bgs_exist_media($id){
        global $wpdb;
        $resources = $wpdb->get_results("SELECT * FROM $wpdb->base_prefix" . "wrn_resources WHERE id='{$id}'", ARRAY_A);
        if(count($resources)>0){
            return true;
        }
        return false;
    }

    //Add media to be shared between levels
    function bgs_add_media($id,$name,$url,$status, $admins,$blog_id){
        global $wpdb;
        if(bgs_exist_media($id)){
            return false;
        }
        $wpdb->query("INSERT INTO $wpdb->base_prefix" . "wrn_resources (id,name,url,status,admins,blog_id) VALUES ('$id','$name','$url','$status','$admins','$blog_id')");
        if($wpdb->insert_id > 0){
            return true;
        }else{
            return $wpdb->last_error;
        }
    }

    //Remove media
    function bgs_remove_media($id){
        global $wpdb;
        $wpdb->query("DELETE FROM $wpdb->base_prefix" . "wrn_resources WHERE id='{$id}'");
        if(wrn_exist_media($id)){
            return false;
        } return true;
    }
