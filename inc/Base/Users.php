<?php


namespace Bgs\Inc\Base;


class Users
{
    public function register(){
        add_filter('user_row_actions', array($this,'bgs_send_rejection_link'), 10, 2);

        //Add the page template for use registration
        add_filter('theme_page_templates', array($this,'bgs_catch_plugin_template'));

        add_filter ('page_template', array($this,'bgs_redirect_page_template'));
    }

    /**
     * @param $actions
     * @param $user
     * @return mixed
     * @description Rejecting and Approving contributors / Sending emails
     */
    function bgs_send_rejection_link($actions, $user) {

        if( isset($_GET['action']) && ($_GET['user'] == $user->user_email) ) {
            if($_GET['action'] == 'bgs_approve_contribution'){
                $user->remove_role('pending_contributor');
                $user->remove_role('subscriber');
                $user->add_role('contributor');
                $sendto = $_GET['user'];
                $sendsub = "Your registration was approved";
                $sendmess = "Congratulation, Your registration was approved";
                $headers = array('From: The Company <email@domain.com>');
                wp_mail($sendto, $sendsub, $sendmess, $headers);
                //echo '<div class="updated notice"><p>Success! The approval email has been sent to ' . $_GET['user'] . '.</p></div>';
            }if($_GET['action'] == 'bgs_send_rejection'){
                $user->remove_role('pending_contributor');
                $user->add_role('subscriber');
                $sendto = $_GET['user'];
                $sendsub = "Your registration has been rejected.";
                $sendmess = "Your registration for has been rejected.";
                $headers = array('From: The Company <email@domain.com>');
                wp_mail($sendto, $sendsub, $sendmess, $headers);
            }

            echo("<script>location.href = '".$_SERVER['HTTP_REFERER']."'</script>");
        }

        if(in_array( 'pending_contributor', $user->roles )){
            $actions['approve_contribution'] = "<a class='wr_approve_contribution' href='" . admin_url( "users.php?action=bgs_approve_contribution&amp;user=" . $user->user_email ) . "'>" . __( 'Approve Contribution' ) . "</a>";
            $actions['send_rejection'] = "<a class='wr_send_rejection' href='" . admin_url( "users.php?action=bgs_send_rejection&amp;user=" . $user->user_email ) . "'>" . __( 'Send Rejection' ) . "</a>";
        }
        return $actions;
    }

    /**
     * @param $templates
     * @return Includes the template to the template array
     */
    function bgs_catch_plugin_template($templates) {
        $templates['template-custom-registration.php'] = 'Custom Register Page';
        return $templates;
    }

    /**
     * @param $template
     * @return Register the template to be used

     */
    function bgs_redirect_page_template ($template) {
        $post = get_post();
        $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
        if ('template-custom-registration.php' == basename ($page_template))
            $template = BGS_PLUGIN_PATH . 'templates/template-custom-registration.php';
        return $template;
    }

}