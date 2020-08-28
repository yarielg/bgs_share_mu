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
        register_taxonomy( "bgs_categories", [ "bg_resources" ], $args );
    }

    function bgs_cpt_actions($actions, $post){
        //check for your post type
        if ($post->post_type == "bgs_resources"){
            // Build your links URL.
            $url = admin_url( 'admin.php?page=mycpt_page&post=' . $post->ID );

            // Maybe put in some extra arguments based on the post status.
            //$edit_link = add_query_arg( array( 'action' => 'edit' ), $url );

            // The default $actions passed has the Edit, Quick-edit and Trash links.
           // $trash = $actions['trash'];

            /*
             * You can reset the default $actions with your own array, or simply merge them
             * here I want to rewrite my Edit link, remove the Quick-link, and introduce a
             * new link 'Copy'
             */
            $actions['send_rejection'] = "<a class='wr_send_rejection' href='" . admin_url( "users.php?action=wr_send_rejection&amp;post=" . $post->ID ) . "'>" . __( 'Send Rejection' ) . "</a>";
            $actions['send_approval'] = "<a class='send_approval' href='" . admin_url( "users.php?action=send_approval&amp;post=" . $post->ID ) . "'>" . __( 'Send Approval' ) . "</a>";

            // You can check if the current user has some custom rights.
            /*if ( current_user_can( 'edit_my_cpt', $post->ID ) ) {

                // Include a nonce in this link
                $copy_link = wp_nonce_url( add_query_arg( array( 'action' => 'copy' ), $url ), 'edit_my_cpt_nonce' );

                // Add the new Copy quick link.
                $actions = array_merge( $actions, array(
                    'copy' => sprintf( '<a href="%1$s">%2$s</a>',
                        esc_url( $copy_link ),
                        'Duplicate'
                    )
                ) );

                // Re-insert thrash link preserved from the default $actions.
                $actions['trash']=$trash;
            }*/
        }
        return $actions;
    }

}