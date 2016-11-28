<?php

class Page extends _Post {

  public $loaded_post = null;

  /**
   * Get the fields for this page type by merging the default template fields
   * with page specific ones
   * @return array The array of fields
   */
  public function getFields() {
    // First figure out what template we're on to get the correct fields
    // If this is the user facing page, then _wp_page_template will be populated
    // and that can be used as the template.
    //
    // If this is the admin facing page, then loadPost() is run to figure out
    // the page ID and the template is determined that way
    if (!empty($this->_wp_page_template)) {
      $template = $this->_wp_page_template;
    } else {
      if(!$this->loadPost() || !Obj::iterable($this->loaded_post)) return [];

      $template = get_page_template_slug($this->loaded_post->ID);
      $this->_wp_page_template = $template;
    }

    $fields_by_template = [];
    if (!empty($template)) {
      $fields_by_template = $this->getFieldsByPageTemplate($template);
    }

    return array_merge(
      $this->getDefaultFields(),
      $fields_by_template
    );
  }


  public function getDefaultFields() {
    return array(
      'other_pages' => array(
        'type' => 'text',
        'class' => 'addbysearch',
        'data-post-type' => 'Page')
    );
  }

  public function getFieldsByPageTemplate($template_file_name) {
    $template_fields = [];

    if($template_file_name === 'tmpl-example.php') {
      $template_fields = array_merge($template_fields, [
        'field_name' => [
          'type' => 'text',
          'description' => '',
        ],
      ]);
    }

    return $template_fields;
  }


  public function isSortable() {
    return true;
  }


  /**
   * This should only be used on the admin side to manually load the post in getFields()
   * because the global $post var isn't accessible when we need it
   */
  public function loadPost() {
    // When we're loading the page, it's in the query string.
    // When we're saving the page, it's in the post vars
    if (!empty($_POST['post_ID'])) {
      $post_id = $_POST['post_ID'];
    } else if (!empty($_GET['post'])) {
      $post_id = $_GET['post'];
    }

    if(empty($post_id)) return false;

    $post_object = get_post($post_id);
    if(is_object($post_object)) {
      $this->loaded_post = $post_object;
      return true;
    }
    return false;
  }
}
