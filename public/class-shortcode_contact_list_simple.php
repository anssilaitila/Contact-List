<?php

class ShortcodeContactListSimple
{
    public static function view( $atts )
    {
        $s = get_option( 'contact_list_settings' );
        $html = '';
        $html .= '<div class="contact-list-simple-container" />';
        $html .= '<div class="contact-list-simple-text-contact" style="display: none;">' . __( 'contact', 'contact-list' ) . '</div>';
        $html .= '<div class="contact-list-simple-text-contacts" style="display: none;">' . __( 'contacts', 'contact-list' ) . '</div>';
        $html .= '<div class="contact-list-simple-text-found" style="display: none;">' . __( 'found', 'contact-list' ) . '</div>';
        $meta_query = array(
            'relation' => 'AND',
        );
        $meta_query[] = array(
            'last_name_clause' => array(
            'key'     => '_cl_last_name',
            'compare' => 'EXISTS',
        ),
        );
        $order_by = array(
            'menu_order'       => 'ASC',
            'last_name_clause' => 'ASC',
            'title'            => 'ASC',
        );
        
        if ( ORDER_BY == '_cl_first_name' ) {
            $meta_query[] = array(
                'first_name_clause' => array(
                'key'     => '_cl_first_name',
                'compare' => 'EXISTS',
            ),
            );
            $order_by = array(
                'menu_order'        => 'ASC',
                'first_name_clause' => 'ASC',
                'title'             => 'ASC',
            );
        }
        
        if ( isset( $_GET['cl_country'] ) && $_GET['cl_country'] ) {
            $meta_query[] = array(
                'key'     => '_cl_country',
                'value'   => $_GET['cl_country'],
                'compare' => 'LIKE',
            );
        }
        if ( isset( $_GET['cl_state'] ) && $_GET['cl_state'] ) {
            $meta_query[] = array(
                'key'     => '_cl_state',
                'value'   => $_GET['cl_state'],
                'compare' => 'LIKE',
            );
        }
        $tax_query = [];
        if ( isset( $_GET['cl_cat'] ) && $_GET['cl_cat'] ) {
            $tax_query = array(
                'relation' => 'AND',
                array(
                'taxonomy' => 'contact-group',
                'field'    => 'slug',
                'terms'    => $_GET['cl_cat'],
            ),
            );
        }
        $wp_query = new WP_Query( array(
            'post_type'      => 'contact',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => $meta_query,
            'tax_query'      => $tax_query,
            'orderby'        => $order_by,
        ) );
        if ( !isset( $atts['hide_search'] ) ) {
            $html .= '<input type="text" class="contact-list-simple-search-contacts" placeholder="' . (( isset( $s['search_contacts'] ) && $s['search_contacts'] ? $s['search_contacts'] : __( 'Search contacts...', 'contact-list' ) )) . '">';
        }
        $html .= '<div class="contact-list-simple-contacts-found"></div>';
        
        if ( $wp_query->have_posts() ) {
            $html .= '<div class="contact-list-simple-ajax-results">';
            $html .= ContactListPublicHelpers::contactListSimpleMarkup( $wp_query );
            $html .= '</div>';
        }
        
        if ( $wp_query->found_posts == 0 ) {
            $html .= '<p>' . ContactListHelpers::getText( 'text_sr_no_contacts_found', __( 'No contacts found.', 'contact-list' ) ) . '</p>';
        }
        $html .= '</div>';
        wp_reset_postdata();
        return $html;
    }

}