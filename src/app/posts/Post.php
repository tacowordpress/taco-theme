<?php

class Post extends _Post {

  public function getFields() {
    return array_merge(parent::getFields(), [
      'created_for_testing' => array('type' => 'checkbox'),
    ]);
  }

  public function getSingular() {
    return 'Post';
  }

  public function getPlural() {
    return 'Posts';
  }

  // Hide extra option from left nav in admin UI
  public function getPostTypeConfig() {
    return null;
  }
}