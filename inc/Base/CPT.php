<?php


namespace Bgs\Inc\Base;


class CPT
{
    public function register(){
        //CPT
        add_action( 'init', array($this,'bgs_register_my_cpts_bg_resources') );
        //Taxonomies
        add_action( 'init', array($this, 'bgs_register_my_taxes_bgs_categories') );
        //Actions
        add_filter('post_row_actions',array($this,'bgs_cpt_actions'), 10, 2);
        //CPT Template
        add_filter( 'single_template', array($this, 'bgs_load_my_custom_template'), 50, 1 );
    }

    function bgs_register_my_cpts_bg_resources() {

        /**
         * Post Type: Resources.
         */

        $labels = [
            "name" => __( "Resources", "bg_sharer_mu" ),
            "singular_name" => __( "Resource", "bg_sharer_mu" ),
            "menu_name" => __( "Resources", "bg_sharer_mu" ),
            "all_items" => __( "All Resources", "bg_sharer_mu" ),
            "add_new" => __( "Add New", "bg_sharer_mu" ),
            "add_new_item" => __( "Add New Resource", "bg_sharer_mu" ),
            "edit_item" => __( "Edit resource", "bg_sharer_mu" ),
            "new_item" => __( "New Resource", "bg_sharer_mu" ),
            "view_item" => __( "View Resource", "bg_sharer_mu" ),
            "view_items" => __( "View Resources", "bg_sharer_mu" ),
            "search_items" => __( "Search Resource", "bg_sharer_mu" ),
            "not_found" => __( "No Resource Found", "bg_sharer_mu" ),
            "not_found_in_trash" => __( "No Resource Found in trash", "bg_sharer_mu" ),
            "parent" => __( "Parent Resource", "bg_sharer_mu" ),
            "parent_item_colon" => __( "Parent Resource", "bg_sharer_mu" ),
        ];

        $args = [
            "label" => __( "Resources", "bg_sharer_mu" ),
            "labels" => $labels,
            "description" => "Used for generate custom entries that will have attachments associated to it",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive" => false,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => [ "slug" => "bgs_resources", "with_front" => true ],
            "query_var" => true,
            "supports" => [ "title", "editor", "thumbnail" ],
            'capabilities' => array(
                'publish_posts' => 'publish_resources',
            ),
        ];

        register_post_type( "bgs_resources", $args );
    }

    function bgs_register_my_taxes_bgs_categories() {

        /**
         * Taxonomy: Resources Categories.
         */

        $labels = [
            "name" => __( "Resources Categories", "bg_sharer_mu" ),
            "singular_name" => __( "Resource Category", "bg_sharer_mu" ),
            "menu_name" => __( "Resources Categories", "bg_sharer_mu" ),
            "all_items" => __( "All Resources Categories", "bg_sharer_mu" ),
            "add_new" => __( "Add New", "bg_sharer_mu" ),
            "add_new_item" => __( "Add New Category", "bg_sharer_mu" ),
            "edit_item" => __( "Edit Category", "bg_sharer_mu" ),
            "new_item" => __( "New Category", "bg_sharer_mu" ),
            "view_item" => __( "View Category Resource", "bg_sharer_mu" ),
            "view_items" => __( "View Category Resources", "bg_sharer_mu" ),
            "search_items" => __( "Search Category Resource", "bg_sharer_mu" ),
            "not_found" => __( "No Category Resource Found", "bg_sharer_mu" ),
            "not_found_in_trash" => __( "No Category Resource Found in trash", "bg_sharer_mu" ),
            "parent" => __( "Parent Category", "bg_sharer_mu" ),
            "parent_item_colon" => __( "Parent Category", "bg_sharer_mu" ),
        ];

        $args = [
            "label" => __( "Resources Categories", "bg_sharer_mu" ),
            "labels" => $labels,
            "public" => true,
            "publicly_queryable" => true,
            "hierarchical" => false,
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => [ 'slug' => 'bgs_categories', 'with_front' => true, ],
            "show_admin_column" => false,
            "show_in_rest" => true,
            "rest_base" => "bgs_categories",
            "rest_controller_class" => "WP_REST_Terms_Controller",
            "show_in_quick_edit" => false,
        ];
        register_taxonomy( "bgs_categories", [ "bgs_resources" ], $args );
    }

    function bgs_cpt_actions($actions, $post){

        $user = wp_get_current_user();

        if(isset($_GET['actiona']) && isset($_GET['post'])) {
            if ($_GET['actiona'] == 'bgs_send_to_parent' && $_GET['post'] == $post->ID ) {
                //Check privilege here
                update_post_meta($post->ID, 'resource_sent', $user->ID);
                $current_site = get_current_blog_id();
                $flag = bgs_add_media($post->ID,$post->post_name, $post->guid,'requested', serialize(array()),$current_site);
                echo $flag ? '<div class="updated notice"><p>Success! The request was sent to higher level</p></div>' :
                             '<div class="updated error"><p>Error! A request was previously send for this resource</p></div>';;
            }
        }

        if ($post->post_type == "bgs_resources"){
            //If the user is an admin from a child website, he can send the approval or the rejection
            if(in_array( 'contributor', $user->roles) && get_current_blog_id() !=-1){
             //   $actions['send_rejection'] = "<a class='wr_send_rejection' href='" . admin_url( "edit.php?post_type=bgs_resources&amp;action=bgs_send_rejection&amp;post=" . $post->ID ) . "'>" . __( 'Send Rejection' ) . "</a>";
             //   $actions['send_approval'] = "<a class='bgs_send_approval' href='" . admin_url( "edit.php?post_type=bgs_resources&amp;action=bgs_send_approval&amp;post=" . $post->ID ) . "'>" . __( 'Send Approval' ) . "</a>";
                $actions['bgs_send_to_parent'] = sprintf('<a href="'.admin_url( "edit.php?post_type=bgs_resources&amp;actiona=%s&amp;post=%s").'">Send to Parent</a>','bgs_send_to_parent',$post->ID);
            }
        }
        return $actions;
    }

    /**
     * Load the cpt template
     */

    function bgs_load_my_custom_template( $template ) {

        if ( is_singular( 'bgs_resources' ) ) {
            $template = BGS_PLUGIN_PATH . 'templates/single-bgs_resources.php';
        }
        return $template;
    }

}