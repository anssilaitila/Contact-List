<?php

class ContactListTaxonomy {

  public function create_contact_list_custom_taxonomy() {

    $labels = array(
      'name' => __('Group', 'contact-list'),
      'singular_name' => __('Group', 'contact-list'),
      'search_items' =>  __('Search Groups', 'contact-list'),
      'all_items' => __('All Groups', 'contact-list'),
      'parent_item' => __('Parent Group', 'contact-list'),
      'parent_item_colon' => __('Parent Group:', 'contact-list'),
      'edit_item' => __('Edit Group', 'contact-list'),
      'update_item' => __('Update Group', 'contact-list'),
      'add_new_item' => __('Add New Group', 'contact-list'),
      'menu_name' => __('Groups', 'contact-list'),
      'not_found' => __('No groups found.', 'contact-list'),
    );

    register_taxonomy('contact-group', array(CONTACT_CPT), array(
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'public' => false,
      'rewrite' => array('slug' => 'groups'),
    ));
  }

  function contact_group_taxonomy_custom_fields($tag) {  
    $t_id = $tag->term_id;
    $term_meta = get_option("taxonomy_term_$t_id");
  ?>  
    
  <tr class="form-field">  
    <th scope="row" valign="top">  
      <label for="term_meta[hide_group]"><?= __('Hide group', 'contact-list'); ?></label>  
      <div style="font-weight: 400; font-style: italic; font-size: 12px; margin-top: 6px;"><?= __('Note: this hides only the group from the front-end views (such as dropdowns and group lists), not the actual contacts that may belong to this group.', 'contact-list') ?></div>
    </th>  
    <td>  
      <input type="checkbox" name="term_meta[hide_group]" id="term_meta[hide_group]" <?= isset($term_meta['hide_group']) ? 'checked="checked"' : ''; ?>>
    </td>  
  </tr>  
    
  <?php  
  }  

  function save_taxonomy_custom_fields($term_id) {  

      $t_id = $term_id;  
      $term_meta = get_option("taxonomy_term_$t_id");  

      if (isset($_POST['term_meta'])) {  
        
        $cat_keys = array_keys($_POST['term_meta']);  

        foreach ($cat_keys as $key) {

          if (isset($_POST['term_meta'][$key])) {
            $term_meta[$key] = $_POST['term_meta'][$key];
          }

        }

      } else {
        $term_meta = array();
      }

      update_option("taxonomy_term_$t_id", $term_meta);  
  }    

  public function theme_columns($theme_columns) {

    $new_columns = array(
      'cb' => '<input type="checkbox" />',
      'name' => __('Name'),
      'shortcode' => __('Shortcode', 'contact-list'),
//      'description' => __('Description'),
      'slug' => __('Slug'),
      'posts' => __('Posts')
    );

    return $new_columns;
  }

  public function add_contact_group_column_content($content, $column_name, $term_id) {

    $term = get_term($term_id, 'contact-group');

    switch ($column_name) {
      case 'shortcode':
        $content = 
        
          '<div class="contact-list-shortcode-admin-list-container">' . 
          '<button class="contact-list-copy contact-list-copy-admin-list contact-list-copy-admin-list-left" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-g-' . $term->slug . '">' . __('Copy', 'contact-list') . '</button>' .
          '<span class="contact-list-shortcode-admin-list contact-list-shortcode-admin-list-right contact-list-shortcode-g-' . $term->slug . '" title="[contact_list_groups group=' . $term->slug . ']">[contact_list_groups group=' . $term->slug . ']</span>' . 
          '<hr class="clear" />' . 
          '</div>' . 

          '<div class="contact-list-shortcode-admin-list-container">' . 
          '<button class="contact-list-copy contact-list-copy-admin-list contact-list-copy-admin-list-left" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-sl-' . $term->slug . '">' . __('Copy', 'contact-list') . '</button>' .
          '<span class="contact-list-shortcode-admin-list contact-list-shortcode-admin-list-right contact-list-shortcode-sl-' . $term->slug . '" title="[contact_list_simple group=' . $term->slug . ']">[contact_list_simple group=' . $term->slug . ']</span>' .
          '<hr class="clear" />' . 
          '</div>';

        break;
      default:
        break;
    }

    return $content;

  }

}
