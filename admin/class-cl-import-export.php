<?php

class ContactListImportExport
{
    public function register_import_page()
    {
        add_submenu_page(
            'edit.php?post_type=' . CONTACT_CPT,
            __( 'Import contacts', 'contact-list' ),
            __( 'Import contacts', 'contact-list' ),
            'manage_options',
            'contact-list-import',
            [ $this, 'register_import_page_callback' ]
        );
    }
    
    public function register_export_page()
    {
        add_submenu_page(
            'edit.php?post_type=' . CONTACT_CPT,
            __( 'Export contacts', 'contact-list' ),
            __( 'Export contacts', 'contact-list' ),
            'manage_options',
            'contact-list-export',
            [ $this, 'register_export_page_callback' ]
        );
    }
    
    public function register_import_page_callback()
    {
        ?>
    
    <div class="wrap">

        <h1><?php 
        echo  esc_html__( 'Import contacts', 'contact-list' ) ;
        ?></h1>

        <p>
            <?php 
        echo  esc_html__( 'You may import contacts from csv file using this form. There should be one contact per row, columns separated by comma.', 'contact-list' ) ;
        ?>
        </p>
        
        <hr class="style-one" />
        
        <?php 
        ?>
          
        <?php 
        
        if ( ContactListHelpers::isPremium() == 0 ) {
            ?>
          <?php 
            echo  ContactListHelpers::proFeatureMarkup() ;
            ?>
        <?php 
        }
        
        ?>
          

        <hr class="style-one" />

        <p>
            <strong><?php 
        echo  esc_html__( 'The columns should be in this order:', 'contact-list' ) ;
        ?></strong>
            <ol>
                <li><?php 
        echo  esc_html__( 'First name', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Last name', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Job title', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Email', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Phone', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'LinkedIn URL', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Twitter URL', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Facebook URL', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Address line 1', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Address line 2', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Address line 3', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Address line 4', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Custom field 1', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Custom field 2', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Custom field 3', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Custom field 4', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Custom field 5', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Custom field 6', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Groups', 'contact-list' ) ;
        ?><i><br /><?php 
        echo  esc_html__( 'Group names separated by the character "|", like so: Cats|Dogs|Parrots', 'contact-list' ) ;
        ?></i></li>
                <li><?php 
        echo  esc_html__( 'Country', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'State', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'City', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'ZIP Code', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Instagram URL', 'contact-list' ) ;
        ?> <span class="new-feature-inline"><?php 
        echo  esc_html__( 'New', 'contact-list' ) ;
        ?></span></li>
            </ol>
        </p>

    </div>
    <?php 
    }
    
    public function register_export_page_callback()
    {
        ?>
    
    <div class="wrap">

        <h1><?php 
        echo  esc_html__( 'Export contacts', 'contact-list' ) ;
        ?></h1>

        <p>
            <?php 
        echo  esc_html__( 'You may export contacts to a csv file. There will be one contact per row, columns separated by comma.', 'contact-list' ) ;
        ?>
        </p>
        
        <hr class="style-one" />

        <?php 
        ?>
  
        <?php 
        
        if ( ContactListHelpers::isPremium() == 0 ) {
            ?>
          <?php 
            echo  ContactListHelpers::proFeatureMarkup() ;
            ?>
        <?php 
        }
        
        ?>
          
        <hr class="style-one" />

        <p>
            <strong><?php 
        echo  esc_html__( 'The columns are in this order:', 'contact-list' ) ;
        ?></strong>
            <ol>
                <li><?php 
        echo  esc_html__( 'First name', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Last name', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Job title', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Email', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Phone', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'LinkedIn URL', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Twitter URL', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Facebook URL', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Address line 1', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Address line 2', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Address line 3', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Address line 4', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Custom field 1', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Custom field 2', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Custom field 3', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Custom field 4', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Custom field 5', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Custom field 6', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'Groups', 'contact-list' ) ;
        ?><i><br /><?php 
        echo  esc_html__( 'Group names separated by the character "|", like so: Cats|Dogs|Parrots', 'contact-list' ) ;
        ?></i></li>
                <li><?php 
        echo  esc_html__( 'Country', 'contact-list' ) ;
        ?></li>
                <li><?php 
        echo  esc_html__( 'State', 'contact-list' ) ;
        ?></span></li>
                <li><?php 
        echo  esc_html__( 'City', 'contact-list' ) ;
        ?></span></li>
                <li><?php 
        echo  esc_html__( 'ZIP Code', 'contact-list' ) ;
        ?></span></li>
                <li><?php 
        echo  esc_html__( 'Instagram URL', 'contact-list' ) ;
        ?> <span class="new-feature-inline"><?php 
        echo  esc_html__( 'New', 'contact-list' ) ;
        ?></span></li>
            </ol>
        </p>

    </div>
    <?php 
    }

}