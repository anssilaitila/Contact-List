<?php

class ContactListAdminSendEmail
{
    public function register_send_email_page()
    {
        add_submenu_page(
            'edit.php?post_type=' . CONTACT_CPT,
            __( 'Send email to contacts', 'contact-list' ),
            __( 'Send email', 'contact-list' ),
            'manage_options',
            'contact-list-send-email',
            [ $this, 'register_send_email_page_callback' ]
        );
    }
    
    public function register_send_email_page_callback()
    {
        $term_id = ( isset( $_GET['group_id'] ) ? $_GET['group_id'] : 0 );
        $tax_query = [];
        if ( $term_id ) {
            $tax_query = array( array(
                'taxonomy'         => 'contact-group',
                'field'            => 'term_id',
                'terms'            => $term_id,
                'include_children' => true,
            ) );
        }
        $wpb_all_query = new WP_Query( array(
            'post_type'      => CONTACT_CPT,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'tax_query'      => $tax_query,
        ) );
        $recipient_emails = [];
        if ( $wpb_all_query->have_posts() ) {
            while ( $wpb_all_query->have_posts() ) {
                $wpb_all_query->the_post();
                $c = get_post_custom();
                if ( isset( $c['_cl_email'] ) && sanitize_email( $c['_cl_email'][0] ) ) {
                    $recipient_emails[] = $c['_cl_email'][0];
                }
            }
        }
        wp_reset_postdata();
        ?>
    
    <div class="wrap">

      <form method="post" class="send_email_form" action="" target="send_email">

          <h1><?php 
        echo  esc_html__( 'Send email to contacts', 'contact-list' ) ;
        ?></h1>

          <?php 
        
        if ( ContactListHelpers::isPremium() == 0 ) {
            ?>  
            <hr class="style-one" />
              <?php 
            echo  ContactListHelpers::proFeatureMarkup() ;
            ?>
            <hr class="style-one" />
          <?php 
        }
        
        ?>

          <div>
            
              <br />
            
              <span class="restrict-recipients-title"><?php 
        echo  esc_html__( 'Restrict recipients to specific group', 'contact-list' ) ;
        ?>:</span>

              <?php 
        $taxonomies = get_terms( array(
            'taxonomy'   => 'contact-group',
            'hide_empty' => false,
        ) );
        $output = '';
        
        if ( !empty($taxonomies) ) {
            foreach ( $taxonomies as $category ) {
                
                if ( $category->parent == 0 ) {
                    $output .= '<label class="contact-category"><input type="radio" name="_cl_groups[]" value="' . esc_attr( $category->term_id ) . '" onclick="document.location.href=\'./edit.php?post_type=' . CONTACT_CPT . '&page=contact-list-send-email&group_id=\' + this.value;" ' . (( isset( $_GET['group_id'] ) && $_GET['group_id'] == $category->term_id ? 'checked' : '' )) . ' /> <span class="contact-list-checkbox-title">' . esc_attr( $category->name ) . '</span></label>';
                    foreach ( $taxonomies as $subcategory ) {
                        if ( $subcategory->parent == $category->term_id ) {
                            $output .= '<label class="contact-subcategory"><input type="radio" name="_cl_groups[]" value="' . esc_attr( $subcategory->term_id ) . '" onclick="document.location.href=\'./edit.php?post_type=' . CONTACT_CPT . '&page=contact-list-send-email&group_id=\' + this.value;" ' . (( isset( $_GET['group_id'] ) && $_GET['group_id'] == $subcategory->term_id ? 'checked' : '' )) . ' /> <span class="contact-list-checkbox-title">' . esc_html( $subcategory->name ) . '</span></label>';
                        }
                    }
                }
            
            }
            echo  '<div class="contact-list-restrict-to-groups">' ;
            echo  $output ;
            echo  '</div>' ;
        } else {
            echo  '<div class="contact-list-admin-no-groups-found">' ;
            echo  esc_html__( 'No groups found.', 'contact-list' ) . ' ' ;
            $url = get_admin_url() . 'edit-tags.php?taxonomy=contact-group&post_type=contact';
            $text = sprintf( wp_kses(
                /* translators: %s: link to group management */
                __( 'You may add groups from <a href="%s">group management</a>.', 'contact-list' ),
                array(
                    'a' => array(
                    'href' => array(),
                ),
                )
            ), esc_url( $url ) );
            echo  $text ;
            echo  '</div>' ;
        }
        
        ?>

          </div>

          <span class="recipients-title"><?php 
        echo  esc_html__( 'Recipients', 'contact-list' ) ;
        ?> (<?php 
        echo  esc_html__( 'total of', 'contact-list' ) ;
        ?> <?php 
        echo  sizeof( $recipient_emails ) ;
        ?> <?php 
        echo  esc_html__( 'contacts with email addresses', 'contact-list' ) ;
        ?>):</span>


          <?php 
        
        if ( sizeof( $recipient_emails ) > 0 ) {
            ?>

            <div><?php 
            echo  implode( ", ", $recipient_emails ) ;
            ?></div>
            <input name="recipient_emails" type="hidden" value="<?php 
            echo  implode( ",", $recipient_emails ) ;
            ?>" />

          <?php 
        } else {
            ?>

            <div class="contact-list-admin-no-contacts-found">

              <?php 
            echo  ContactListHelpers::getText( 'text_sr_no_contacts_found', __( 'No contacts found.', 'contact-list' ) ) ;
            ?>

              <?php 
            $url = get_admin_url() . 'edit.php?post_type=' . CONTACT_CPT;
            $text = sprintf( wp_kses(
                /* translators: %s: link to contact management */
                __( 'You may add contacts or assign them to groups from <a href="%s">contact management</a>.', 'contact-list' ),
                array(
                    'a' => array(
                    'href' => array(),
                ),
                )
            ), esc_url( $url ) );
            echo  $text ;
            ?>

            </div>

          <?php 
        }
        
        ?>

          <hr class="style-one" />

          <label>
            <span><?php 
        echo  esc_html__( 'Subject', 'contact-list' ) ;
        ?></span>
            <input name="subject" type="text" value="" required />
          </label>

          <?php 
        $user_id = get_current_user_id();
        ?>
          <?php 
        $user = get_userdata( $user_id );
        ?>
          
          <label>
            <span><?php 
        echo  esc_html__( 'Sender name', 'contact-list' ) ;
        ?></span>
            <input name="sender_name" type="text" value="" required />
          </label>

          <label>
            <span><?php 
        echo  esc_html__( 'Sender email', 'contact-list' ) ;
        ?></span>
            <input name="sender_email" type="email" value="" required />
          </label>
    
          <label>
            <span><?php 
        echo  esc_html__( 'Message', 'contact-list' ) ;
        ?></span>
            <textarea name="body" required></textarea>
          </label>

          <div class="send_email_target_div"></div>
  
          <input type="submit" value="<?php 
        echo  esc_attr__( 'Send', 'contact-list' ) ;
        ?>" <?php 
        if ( sizeof( $recipient_emails ) == 0 || ContactListHelpers::isPremium() == 0 ) {
            echo  'disabled' ;
        }
        ?> />
          <hr class="style-one" />
          
      </form>

    </div>
    <?php 
    }
    
    public function cl_send_mail()
    {
        $s = get_option( 'contact_list_settings' );
        $subject = ( isset( $_POST['subject'] ) ? $_POST['subject'] : '' );
        $sender_name = ( isset( $_POST['sender_name'] ) ? $_POST['sender_name'] : '' );
        $sender_email = ( isset( $_POST['sender_email'] ) ? $_POST['sender_email'] : '' );
        $mail_cnt = ( isset( $_POST['mail_cnt'] ) ? $_POST['mail_cnt'] : '' );
        $reply_to = ( isset( $_POST['reply_to'] ) ? $_POST['reply_to'] : '' );
        $body = ( isset( $_POST['body'] ) ? nl2br( stripslashes( $_POST['body'] ) ) : '' );
        if ( !isset( $s['remove_email_footer'] ) ) {
            $body .= '<br /><br />-- <br />' . ContactListHelpers::getText( 'text_email_footer', __( 'Sent by Contact List Pro', 'contact-list' ) );
        }
        $headers = [ 'Content-Type: text/html; charset=UTF-8' ];
        if ( $sender_name && $sender_email && is_email( $sender_email ) ) {
            $headers[] .= 'From: ' . esc_attr( $sender_name ) . ' <' . sanitize_email( $sender_email ) . '>';
        }
        $reply_to_headers = '';
        
        if ( $sender_name && is_email( $reply_to ) ) {
            $reply_to_headers = $sender_name . ' <' . $reply_to . '>';
        } elseif ( is_email( $reply_to ) ) {
            $reply_to_headers = '<' . $reply_to . '>';
        }
        
        if ( $reply_to_headers ) {
            $headers[] = 'Reply-To: ' . $reply_to_headers;
        }
        $recipient_emails = ( isset( $_POST['recipient_emails'] ) ? $_POST['recipient_emails'] : '' );
        $resp = wp_mail(
            $recipient_emails,
            $subject,
            $body,
            $headers
        );
        
        if ( $resp && !isset( $s['disable_mail_log'] ) ) {
            global  $wpdb ;
            $report = 'Mail successfully processed using <strong>wp_mail</strong>.<br /><br /><strong>Full list of recipient(s):</strong><br />' . str_replace( ',', ', ', $recipient_emails );
            $all_emails = explode( ',', $recipient_emails );
            $mail_cnt = sizeof( $all_emails );
            $wpdb->insert( $wpdb->prefix . 'cl_sent_mail_log', array(
                'subject'      => $subject,
                'sender_name'  => $sender_name,
                'reply_to'     => $reply_to,
                'report'       => $report,
                'sender_email' => $sender_email,
                'mail_cnt'     => $mail_cnt,
            ) );
        }
        
        
        if ( $resp ) {
            echo  'OK' ;
        } else {
            echo  'ERROR' ;
        }
        
        die;
    }
    
    public function cl_request_update()
    {
        $s = get_option( 'contact_list_settings' );
        $site_url = get_site_url();
        $site_url_parts = parse_url( $site_url );
        $post_id = ( isset( $_POST['contact_id'] ) ? $_POST['contact_id'] : '' );
        $valid_period = 60 * 60 * 24 * 2;
        // 60 minutes * 24 * 2
        $expiry = current_time( 'timestamp', 1 ) + $valid_period;
        $url = site_url( '/_cl_update-contact/' . $post_id . '/' );
        $url = add_query_arg( 'valid', $expiry, $url );
        $url = add_query_arg( 'sc', md5( $post_id . $expiry . get_option( 'contact-list-sc' ) ), $url );
        $update_url = $url;
        $subject = ContactListHelpers::getText( 'request_update_mail_subject', __( 'Update request from', 'contact-list' ) ) . ' ' . $site_url_parts['host'];
        $sender_name = '';
        $sender_email = '';
        $body_content = '<p style="color: #000;">' . ContactListHelpers::getText( 'request_update_mail_content', __( 'You have been requested to update your contact info on site.', 'contact-list' ) ) . '</p>' . '<p><a href="' . $update_url . '">' . ContactListHelpers::getText( 'request_update_link_text', __( 'Update contact info', 'contact-list' ) ) . ' &raquo;</a></p>';
        $body = nl2br( stripslashes( $body_content ) );
        if ( !isset( $s['remove_email_footer'] ) ) {
            $body .= '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                <tr>
                  <td style="border-top: 1px solid #eee; color: #bbb; padding-top: 3px; width: 100%;">
                    ' . ContactListHelpers::getText( 'text_email_footer', __( 'Sent by Contact List Pro', 'contact-list' ) ) . '
                  </td>
                </tr>
                </table>';
        }
        $headers = [ 'Content-Type: text/html; charset=UTF-8' ];
        if ( $sender_name && $sender_email && is_email( $sender_email ) ) {
            $headers[] .= 'From: ' . esc_attr( $sender_name ) . ' <' . sanitize_email( $sender_email ) . '>';
        }
        $recipient_email = get_post_meta( $post_id, '_cl_email', true );
        $resp = wp_mail(
            $recipient_email,
            $subject,
            $body,
            $headers
        );
        
        if ( $resp && !isset( $s['disable_mail_log'] ) ) {
            global  $wpdb ;
            $report = 'Mail successfully processed using <strong>wp_mail</strong>.<br /><br /><strong>Full list of recipient(s):</strong><br />' . str_replace( ',', ', ', $recipient_email );
            $all_emails = explode( ',', $recipient_email );
            $mail_cnt = 1;
            $wpdb->insert( $wpdb->prefix . 'cl_sent_mail_log', array(
                'subject'      => $subject,
                'sender_name'  => $sender_name,
                'report'       => $report,
                'sender_email' => $sender_email,
                'mail_cnt'     => $mail_cnt,
            ) );
        }
        
        
        if ( $resp ) {
            echo  'OK' ;
        } else {
            echo  'ERROR' ;
        }
        
        die;
    }
    
    public static function cl_send_permanent_update_url( $post_id )
    {
    }
    
    public function new_contact_send_email( $post_id, $post, $update )
    {
        $post_title = get_the_title( $post_id );
        $s = get_option( 'contact_list_settings' );
        
        if ( isset( $s['send_email'] ) && isset( $s['recipient_email'] ) && is_email( $s['recipient_email'] ) && $post->post_type == CONTACT_CPT && ($post->post_status == 'pending' || $post->post_status == 'draft') ) {
            $contact_list_admin_url = get_admin_url() . 'edit.php?post_type=' . CONTACT_CPT;
            $data = array(
                'post_title'      => $post_title,
                'recipient_email' => $s['recipient_email'],
                'url'             => $contact_list_admin_url,
            );
            $headers = array( 'Content-Type: text/html; charset=UTF-8' );
            $subject = 'New contact: ' . $post_title;
            $body_html = '';
            $body_html .= '<html><head><title></title></head><body>';
            $body_html .= '<h3 style="color: #000;">New contact was added: ' . $post_title . '</h3>';
            $body_html .= '<p style="color: #000;">See the full details here: ' . $contact_list_admin_url . '</p>';
            $body_html .= '<p style="color: #bbb;">-- <br />Sent by Contact List Pro</p>';
            $body_html .= '</body></html>';
            $resp = wp_mail(
                $s['recipient_email'],
                $subject,
                $body_html,
                $headers
            );
            
            if ( $resp && !isset( $s['disable_mail_log'] ) ) {
                global  $wpdb ;
                $report = 'Mail successfully processed using <strong>wp_mail</strong>.<br /><br /><strong>Full list of recipient(s):</strong><br />' . $s['recipient_email'];
                $wpdb->insert( $wpdb->prefix . 'cl_sent_mail_log', array(
                    'subject'  => $subject,
                    'report'   => $report,
                    'mail_cnt' => 1,
                ) );
            }
        
        }
    
    }
    
    public function wp_mail_returnpath_phpmailer_init( $phpmailer )
    {
        $s = get_option( 'contact_list_settings' );
        // Set the Sender (return-path)
        if ( isset( $s['set_return_path'] ) ) {
            // && filter_var($params->Sender, FILTER_VALIDATE_EMAIL) !== true) {
            $phpmailer->Sender = $phpmailer->From;
        }
    }

}