<?php


namespace Bgs\Inc\Base;


class Users
{
    public function register(){
        add_filter('user_row_actions', array($this,'bgs_send_rejection_link'), 10, 2);
    }

    // Rejecting and Approving contributors / Sending emails
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

}