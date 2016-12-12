<?php

class _Post extends \Taco\Post {
  use Pagination;
  
  
  public static function isLoadable() {
    return (static::class !== __CLASS__);
  }
  
  
  public function getFields() {
    return [];
  }
  
  
  public function getHierarchical() {
    return $this->isSortable();
  }
  
  
  public function isSortable() {
    return false;
  }
  
  
  public function getPostTypeConfig() {
    if(static::class === __CLASS__) return null;
    
    return parent::getPostTypeConfig();
  }
  
  
  /**
   * Get all subclasses
   * @return array
   */
  public static function getSubclasses() {
    return array_filter(get_declared_classes(), function($class){
      return is_subclass_of($class, static::class);
    });
  }
  
}
